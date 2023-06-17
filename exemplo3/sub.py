import random
import re
import mysql.connector
from mysql.connector import Error
from datetime import date
from datetime import time
from paho.mqtt import client as mqtt_client
from pykafka import KafkaClient
from kafka import KafkaConsumer
from kafka.admin import KafkaAdminClient, NewTopic 

# O broker daqui provavelmente vai acabar sendo o kafka
broker = 'localhost'
port = 1883
topic = "EMA01"
# generate client ID with pub prefix randomly
client_id = f'python-mqtt-{random.randint(0, 100)}'

connection = mysql.connector.connect(host='localhost',
                                             database='awsmqtt',
                                             user='root',
                                             password='root')
                                             
                                             
#Configurações do Kafka
admin_client = KafkaAdminClient(bootstrap_servers=['localhost:9092'])

consumer = KafkaConsumer("EMA01", bootstrap_servers = "localhost",value_deserializer=lambda v: json.dumps(v).encode("ascii"), auto_offset_reset='earliest',)

kafka_client = KafkaClient(hosts='localhost:9092',)
kafka_topic = kafka_client.topics["EMA01"]
kafka_producer = kafka_topic.get_sync_producer()

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

def ver_topicos():
    topicos_existentes = consumer.topics()
    print(list(topicos_existentes))

def criar_topico(topico):
    topicos_existentes = consumer.topics()
    print(list(topicos_existentes))
    lista_topicos = []
    if topico not in topicos_existentes:
        print(f"Topico: {topico} adicionado na lista.")
        lista_topicos.append(NewTopic(name=topico, num_partitions=1, replication_factor=1))
    else:
        print("Este tópico já existe na lista.")
    try:
        if lista_topicos:
            admin_client.create_topics(new_topics = lista_topicos, validate_only=False,)
            print("Tópico criado com sucesso.")
        else:
            print("Tópico já existe.")
    except TopicAlreadyExistsError as error:
        print(error)
    except Exception as error:
        print(error)

def deletar_topico(topico):
    try:
        admin_client.delete_topics(topics=topic_names)
        print("Tópico deletado.")
    except UnknowTopicOrPartitionError as error:
        print(error)
    except Exception as error:
        print(error)
        
def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
    
    	#### Conectando ao banco de dados
        connect_database()
        cursor = connection.cursor()
    	
	# Pegando os dados da mensagem recebida e separando eles
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
        
        #### Os dados devem ser enviados pelo banco depois apenas, 
	#### aqui vou enviar apenas do MQTT para o Kafka, depois será 
	#### receber a mensagem do kafka e mandar para o MySQL
        #### Enviando para o banco de dados
        query = 'INSERT INTO relatorios(data,hora,temperatura,pluviometro,vel_vento,dir_vento,emas_idema,emas_usuarios_idusuario) '
        query+= f'VALUES("{data_atual}","{hora_atual}",{temperatura},{pluviometro},{vel_vento},{dir_vento},1,1)'
        cursor.execute(query)
        connection.commit() # Altera o banco
        
        
        
        kafka_producer.produce(msg.encode())
        print(f"\nKafka publicou para o tópico {kafka_topic}\n {msg}")

    client.subscribe(topic, qos=1)
    client.on_message = on_message
    
    
def run():
    client = connect_mqtt()
    subscribe(client)
    client.loop_forever()
    


if __name__ == '__main__':
    run()
