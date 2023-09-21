import random
import mysql.connector
import json
from mysql.connector import Error
from datetime import date, time
from paho.mqtt import client as mqtt_client
from kafka import KafkaConsumer

arquivo_de_config= open('./jsons/ema02.json', encoding="utf8")
ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = ema['topico']
client_id = f'python-mqtt-{random.randint(0, 100)}'
consumer = KafkaConsumer('morrigan1', bootstrap_servers='localhost:9092')
connection = mysql.connector.connect(host='localhost', database='awsmqtt', user='root', password='ifsp')


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
    relatorio = json.loads(aux.read())
    print(relatorio)

def run():
    connect_database()
    for msg in consumer:
        aux = msg.value.decode('utf-8')
        persistir_msg(aux)
    


if __name__ == '__main__':
    run()