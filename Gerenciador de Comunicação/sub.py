import random
import re
import mysql.connector
from mysql.connector import Error
from datetime import date, time
from paho.mqtt import client as mqtt_client

broker = 'localhost'
port = 1883
topic ="morrigan1"
# generate client ID with pub prefix randomly
client_id = f'python-mqtt-{random.randint(0, 100)}'

connection = mysql.connector.connect(host='localhost',database='awsmqtt', user='root', password='ifsp')

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
            
def connect_mqtt() -> mqtt_client:
    def on_connect(client, userdata, flags, rc):
        if rc == 0:
            print("Conectado ao Broker!")
        else:
            print("Conexão falhou, código: %d\n", rc)

    client = mqtt_client.Client(client_id)
    client.on_connect = on_connect
    client.connect(broker, port)
    return client

def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
    
    	#### Conectando ao banco de dados
        connect_database()
        cursor = connection.cursor()

	    # Pegando os dados da mensagem recebida e separando eles
        aux = msg.payload.decode()
        print(f"Mensagem recebida pelo tópico {topic}:{msg}\n")
        
    client.subscribe(topic, qos=1)
    client.on_message = on_message
    
    
def run():
    client = connect_mqtt()
    subscribe(client)
    client.loop_forever()
    


if __name__ == '__main__':
    run()
