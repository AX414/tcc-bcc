import random
import mysql.connector
import json
import jsonschema
from jsonschema import validate
from mysql.connector import Error
from datetime import date, time
from paho.mqtt import client as mqtt_client
from kafka import KafkaConsumer

arquivo_de_config= open('../jsons/ema01.json', encoding="utf8")
ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = ema['topico']
client_id = f'python-mqtt-{random.randint(0, 100)}'
consumer = KafkaConsumer(ema['topico'], bootstrap_servers='localhost:9092')
connection = mysql.connector.connect(host='localhost', database='awsmqtt', user='root', password='ifsp')
cursor = connection.cursor()

with open('../jsons/schema.json', encoding="utf8") as schema:
    json_schema = json.load(schema)

def connect_database():
    try:
        if connection.is_connected():
            db_info = connection.get_server_info()
            print(f"\nConectado ao MySQL Server {db_info}")
            cursor = connection.cursor()
            cursor.execute("select database();")
            record = cursor.fetchone()
            print(f"Conectado ao banco de dados {record}")
    except Exception as e:
        print(f"Erro: {e}")

def disconnect_database():
    cursor = connection.cursor()
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("Conexão encerrada")

def validar_mensagem(mensagem):
    try:
        validate(instance=mensagem, schema=json_schema)
        print("\nA mensagem é válida de acordo com o JSON Schema.")
        return True
    except jsonschema.exceptions.ValidationError as e:
        print(f"A mensagem não é válida de acordo com o JSON Schema: {e}")
        return False

def persistir_msg(aux):
    relatorio = json.loads(aux)

    idema = relatorio['idema']
    data_leitura = relatorio['data_leitura']
    hora_leitura = relatorio['hora_leitura']
    
    # Campos Obrigatórios
    temperatura = relatorio['obrigatorio']['temperatura']['valor']
    unidade_tem = relatorio['obrigatorio']['temperatura']['unidade']
    erro_tem = False
    umidade = relatorio['obrigatorio']['umidade']['valor']
    unidade_um = relatorio['obrigatorio']['umidade']['unidade']
    erro_um = False
    velocidade_vento = relatorio['obrigatorio']['velocidade_vento']['valor']
    unidade_vv = relatorio['obrigatorio']['velocidade_vento']['unidade']
    erro_vv = False
    direcao_vento = relatorio['obrigatorio']['direcao_vento']['valor']
    unidade_dv = relatorio['obrigatorio']['direcao_vento']['unidade']
    erro_dv = False
    
    # Campos Opcionais
    radiacao_solar = relatorio['opcional']['radiacao_solar']['valor']
    unidade_rs = relatorio['opcional']['radiacao_solar']['unidade']
    erro_rs = False
    pressao_atmos = relatorio['opcional']['pressao_atmos']['valor']
    unidade_pa = relatorio['opcional']['pressao_atmos']['unidade']
    erro_pa = False
    volume_chuva = relatorio['opcional']['volume_chuva']['valor']
    unidade_vc = relatorio['opcional']['radiacao_solar']['unidade']
    erro_vc = False
    frequencia_chuva = relatorio['opcional']['frequencia_chuva']['valor']
    unidade_fc = relatorio['opcional']['frequencia_chuva']['unidade']
    erro_fc = False
    
    # Dados não previstos
    nao_previstos = json.dumps(relatorio['nao_previstos'])
    erros = ""
    idema = ema['idema']
    usuarios_idusuario = ema['usuarios_idusuario']

    if(validar_mensagem(relatorio)):
        print("\nMensagem válida de acordo com o JSON Schema.")
    else:
        print("\nMensagem inválida de acordo com o JSON Schema. Persistindo com valores de erro.")
        erros+="\nErro no JSON, ele não estava de acordo com o JSON Schema utilizado."

    print(f"Mensagem consumida pelo tópico: {topic}.")
    print("================================================================================================")
    print(f"Mensagem recebida: {relatorio}")
    print("================================================================================================\n")
    query = 'INSERT INTO relatorios(data, hora, temperatura, unidade_tem, erro_tem,'
    query += 'umidade, unidade_um, erro_um, vento_velocidade, unidade_vv, erro_vv,'
    query += 'vento_direcao, unidade_vd, erro_vd, radiacao_solar, unidade_rs, erro_rs,'
    query += 'pressao_atmos, unidade_pa, erro_pa, volume_chuva, unidade_vc, erro_vc,'
    query += 'frequencia_chuva, unidade_fc, erro_fc, nao_previstos, erros,'
    query += 'emas_idema, emas_usuarios_idusuario) '
    query += f'VALUES("{data_leitura}", "{hora_leitura}", {temperatura}, "{unidade_tem}", {erro_tem}, {umidade}, "{unidade_um}", {erro_um},{velocidade_vento}, "{unidade_vv}", {erro_vv}, {direcao_vento}, "{unidade_dv}", {erro_dv}, {radiacao_solar}, "{unidade_rs}", {erro_rs}, {pressao_atmos}, "{unidade_pa}", {erro_pa}, {volume_chuva}, "{unidade_vc}", {erro_vc},{frequencia_chuva}, "{unidade_fc}", {erro_fc}, \'{nao_previstos}\',"{erros}", {idema}, {usuarios_idusuario})'
    cursor.execute(query)
    connection.commit()

def run():
    connect_database()
    for msg in consumer:
        aux = msg.value.decode('utf-8')
        persistir_msg(aux)
    consumer.close()

if __name__ == '__main__':
    run()