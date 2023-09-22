import random
import mysql.connector
import json
from mysql.connector import Error
from datetime import date, time
from paho.mqtt import client as mqtt_client
from kafka import KafkaConsumer

arquivo_de_config= open('./jsons/ema01.json', encoding="utf8")
ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = ema['topico']
client_id = f'python-mqtt-{random.randint(0, 100)}'
consumer = KafkaConsumer(ema['topico'], bootstrap_servers='localhost:9092')
connection = mysql.connector.connect(host='localhost', database='awsmqtt', user='root', password='ifsp')
cursor = connection.cursor()

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

def persistir_msg(aux):
    relatorio = json.loads(aux)

    idema = relatorio['idema']
    data_leitura = relatorio['data_leitura']
    hora_leitura = relatorio['hora_leitura']
    
    # Campos Obrigatórios
    temperatura = relatorio['obrigatorio']['temperatura']['valor']
    unidade_tem = relatorio['obrigatorio']['temperatura']['unidade']
    umidade = relatorio['obrigatorio']['umidade']['valor']
    unidade_um = relatorio['obrigatorio']['umidade']['unidade']
    velocidade_vento = relatorio['obrigatorio']['velocidade_vento']['valor']
    unidade_vv = relatorio['obrigatorio']['velocidade_vento']['unidade']
    direcao_vento = relatorio['obrigatorio']['direcao_vento']['valor']
    unidade_dv = relatorio['obrigatorio']['direcao_vento']['unidade']
    
    # Campos Opcionais
    radiacao_solar = relatorio['opcional']['radiacao_solar']['valor']
    unidade_rs = relatorio['opcional']['radiacao_solar']['unidade']
    radiacao_solar = relatorio['opcional']['radiacao_solar']['valor']
    unidade_rs = relatorio['opcional']['radiacao_solar']['unidade']
    pressao_atmos = relatorio['opcional']['pressao_atmos']['valor']
    unidade_pa = relatorio['opcional']['pressao_atmos']['unidade']
    volume_chuva = relatorio['opcional']['volume_chuva']['valor']
    unidade_vc = relatorio['opcional']['radiacao_solar']['unidade']
    frequencia_chuva = relatorio['opcional']['frequencia_chuva']['valor']
    unidade_fc = relatorio['opcional']['frequencia_chuva']['unidade']
    
    # Dados não previstos
    nao_previstos = json.dumps(relatorio['nao_previstos'])

    print(f"\nMensagem consumida pelo tópico: {topic}.")
    print("================================================================================================")
    print(f"Mensagem recebida: {relatorio}")
    print("================================================================================================\n")
    query = 'INSERT INTO relatorios(data, hora, temperatura, unidade_tem, umidade, unidade_um, vento_velocidade, unidade_vv, vento_direcao, unidade_vd, radiacao_solar, unidade_rs, pressao_atmos, unidade_pa, volume_chuva, unidade_vc, frequencia_chuva, unidade_fc, nao_previstos, erro, emas_idema, emas_usuarios_idusuario) '
    query += f'VALUES("{data_leitura}", "{hora_leitura}", {temperatura}, "{unidade_tem}", {umidade}, "{unidade_um}", {velocidade_vento}, "{unidade_vv}", {direcao_vento}, "{unidade_dv}", {radiacao_solar}, "{unidade_rs}", {pressao_atmos}, "{unidade_pa}", {volume_chuva}, "{unidade_vc}", {frequencia_chuva}, "{unidade_fc}", \'{nao_previstos}\', 0, 1, 1)'
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