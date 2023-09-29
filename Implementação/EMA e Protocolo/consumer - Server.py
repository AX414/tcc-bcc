import random
import mysql.connector
import json
import jsonschema
from jsonschema import validate
from mysql.connector import Error
from datetime import date, time
from paho.mqtt import client as mqtt_client
from kafka import KafkaConsumer

arquivo_de_config= open('./jsons/emas/ema01.json', encoding="utf8")
ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = ema['topico']
client_id = f'python-mqtt-{random.randint(0, 100)}'
consumer = KafkaConsumer(ema['topico'], bootstrap_servers='localhost:9092')
connection = mysql.connector.connect(host='localhost', database='awsmqtt', user='root', password='ifsp')
cursor = connection.cursor()

with open('./jsons/schema.json', encoding="utf8") as schema:
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

def encontrar_idema(topico):
    try:
        cursor = connection.cursor()
        query = f"SELECT idema FROM emas WHERE topico_kafka = '{topico}'"
        cursor.execute(query)
        result = cursor.fetchone()
        if result:
            idema = result[0]
            return idema
        else:
            print(f"EMA com o tópico '{topico}' não encontrado.")
            return None
    except Exception as e:
        print(f"Erro ao encontrar EMA: {e}")
        return None


def persistir_msg(aux):
    relatorio = json.loads(aux)
    erros = ""
    idema = relatorio['idema']
    data_leitura = relatorio['data_leitura']
    hora_leitura = relatorio['hora_leitura']
    
    # Campos Obrigatórios
    temperatura = relatorio['obrigatorio']['temperatura']['valor']
    unidade_tem = relatorio['obrigatorio']['temperatura']['unidade']
    erro_tem = False
    if(temperatura<-20 or temperatura>45):
        erro_tem = True
        erros += "\nErro no sensor de Temperatura."

    umidade = relatorio['obrigatorio']['umidade']['valor']
    unidade_um = relatorio['obrigatorio']['umidade']['unidade']
    erro_um = False
    if(umidade<0 or umidade>100):
        erro_um = True
        erros+= "\nErro no sensor de Umidade."

    velocidade_vento = relatorio['obrigatorio']['velocidade_vento']['valor']
    unidade_vv = relatorio['obrigatorio']['velocidade_vento']['unidade']
    erro_vv = False
    if(velocidade_vento<0 or velocidade_vento>50):
        erro_vv = True
        erros+= "\nErro no sensor de Velocidade do Vento."

    direcao_vento = relatorio['obrigatorio']['direcao_vento']['valor']
    unidade_dv = relatorio['obrigatorio']['direcao_vento']['unidade']
    erro_dv = False
    if(direcao_vento<0 or direcao_vento>360):
        erro_dv = True
        erros+= "\nErro no sensor de Direção do Vento."

    # Campos Opcionais
    radiacao_solar = relatorio['opcional']['radiacao_solar']['valor']
    unidade_rs = relatorio['opcional']['radiacao_solar']['unidade']
    erro_rs = False
    if(radiacao_solar<0 or velocidade_vento>1500):
        erro_rs = True
        erros+= "\nErro no sensor de Radiação Solar."
    
    pressao_atmos = relatorio['opcional']['pressao_atmos']['valor']
    unidade_pa = relatorio['opcional']['pressao_atmos']['unidade']
    erro_pa = False
    if(pressao_atmos<800 and pressao_atmos>1100):
        erro_pa = True
        erros+= "\nErro no sensor de Pressão Atmosférica."
    
    volume_chuva = relatorio['opcional']['volume_chuva']['valor']
    unidade_vc = relatorio['opcional']['radiacao_solar']['unidade']
    erro_vc = False
    if(volume_chuva<0 and volume_chuva>100):
        erro_vc = True
        erros+= "\nErro no sensor de Volume da Chuva."
    
    frequencia_chuva = relatorio['opcional']['frequencia_chuva']['valor']
    unidade_fc = relatorio['opcional']['frequencia_chuva']['unidade']
    erro_fc = False
    if(frequencia_chuva<0 and frequencia_chuva>100):
        erro_fc = True
        erros+= "\nErro no sensor de Frequência da Chuva."

    # Dados não previstos
    nao_previstos = json.dumps(relatorio['nao_previstos'])
    erros = ""
    idema = ema['topico']

    if(validar_mensagem(relatorio)):
        print("\nMensagem válida de acordo com o JSON Schema.")
    else:
        print("\nMensagem inválida de acordo com o JSON Schema. Persistindo com valores de erro.")
        erros+="\nErro no JSON, ele não estava de acordo com o JSON Schema utilizado."

    print(f"Mensagem consumida pelo tópico: {topic}.")
    print("================================================================================================")
    print(f"Mensagem recebida: {relatorio}")
    print("================================================================================================\n")
    
    # Pegando a EMA pelo Tópico dela
    idema = encontrar_idema(ema['topico'])

    if idema is not None:
        query = 'INSERT INTO relatorios(data, hora, temperatura, unidade_tem, erro_tem,'
        query += 'umidade, unidade_um, erro_um, vento_velocidade, unidade_vv, erro_vv,'
        query += 'vento_direcao, unidade_vd, erro_vd, radiacao_solar, unidade_rs, erro_rs,'
        query += 'pressao_atmos, unidade_pa, erro_pa, volume_chuva, unidade_vc, erro_vc,'
        query += 'frequencia_chuva, unidade_fc, erro_fc, nao_previstos, erros,'
        query += 'emas_idema) '
        query += f'VALUES("{data_leitura}", "{hora_leitura}", {temperatura}, "{unidade_tem}", {erro_tem}, {umidade}, "{unidade_um}", {erro_um},{velocidade_vento}, "{unidade_vv}", {erro_vv}, {direcao_vento}, "{unidade_dv}", {erro_dv}, {radiacao_solar}, "{unidade_rs}", {erro_rs}, {pressao_atmos}, "{unidade_pa}", {erro_pa}, {volume_chuva}, "{unidade_vc}", {erro_vc},{frequencia_chuva}, "{unidade_fc}", {erro_fc}, \'{nao_previstos}\',"{erros}", {idema})'
        cursor.execute(query)
        connection.commit()
    else:
        print("Não foi possível encontrar a EMA correspondente.")

def run():
    connect_database()
    for msg in consumer:
        aux = msg.value.decode('utf-8')
        persistir_msg(aux)
    consumer.close()

if __name__ == '__main__':
    run()