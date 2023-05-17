import random
import re
import mysql.connector
from mysql.connector import Error
from datetime import date
from datetime import time
from paho.mqtt import client as mqtt_client

# O broker daqui provavelmente vai acabar sendo o kafka
broker = 'localhost'
port = 1883
topic = "EMA/mqtt"
# generate client ID with pub prefix randomly
client_id = f'python-mqtt-{random.randint(0, 100)}'

connection = mysql.connector.connect(host='localhost',
                                             database='awsmqtt',
                                             user='root',
                                             password='root')
def connect_database():
    try:
        if connection.is_connected():
            db_info = connection.get_server_info()
            print(f"\nConectado ao MySQL Server {db_info}")
            cursor = connection.cursor()
            cursor.execute("select database();")
            record = cursor.fetchone()
            print(f"Conectado ao banco de dados {record}")
    except Error as e:
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
    
    	# Conectando ao banco de dados
        connect_database()
        cursor = connection.cursor()
    	
	# Pegando os dados da mensagem recebida e separando eles
	# para apresentar na mensagem formatada r"[\w']+"
        aux = msg.payload.decode()
        dados = aux.split(",")
        data_atual = dados[0]     
        hora_atual = dados[1]
        latitude = dados[2]
        longitude = dados[3]
        temperatura = dados[4]
        pluviometro = dados[5]
        vel_vento = dados[6]
        dir_vento = dados[7]
        # Apresentando os dados
	
        msg = f"\n-------------------------------------"
        msg+= f"\nData atual: {data_atual}"
        msg+= f"\nHora: {hora_atual}"
        msg+= f"\nLatitude: {latitude}"
        msg+= f"\nLongitude: {longitude}"
        msg+= f"\nTemperatura: {temperatura} ºC"
        msg+= f"\nPluviometro: {pluviometro} mm"
        msg+= f"\nVelocidade do vento: {vel_vento} m/s"
        msg+= f"\nDireção do vento: {dir_vento}º"
        msg+= f"\n-------------------------------------"

        print(f"Mensagem recebida pelo tópico {topic}:{msg}\n")
        # Enviando para o banco de dados
        query = 'INSERT INTO relatorios(data,hora,temperatura,pluviometro,vel_vento,dir_vento,emas_idema,emas_usuarios_idusuario) '
        query+= f'VALUES("{data_atual}","{hora_atual}",{temperatura},{pluviometro},{vel_vento},{dir_vento},1,1)'
        cursor.execute(query)
        connection.commit() # Altera o banco
        # Quando desconectar? disconnect_database()

    client.subscribe(topic)
    client.on_message = on_message
    
def run():
    client = connect_mqtt()
    subscribe(client)
    client.loop_forever()
    


if __name__ == '__main__':
    run()
