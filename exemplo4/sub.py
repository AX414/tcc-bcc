import random
import json
from datetime import date, time
from paho.mqtt import client as mqtt_client
from pymongo import MongoClient
from pykafka import KafkaClient
from kafka import KafkaConsumer
from kafka.admin import KafkaAdminClient, NewTopic 

broker = 'localhost'
port = 1883
topic = "EMA01"
client_id = f'python-mqtt-{random.randint(0, 100)}'

mongo_uri = "mongodb://localhost:27017/"
database_name = "awsmqtt"
collection_name = "relatorios"

mongo_client = MongoClient(mongo_uri)
mongo_db = mongo_client[database_name]
mongo_collection = mongo_db[collection_name]

#Configurações do Kafka
admin_client = KafkaAdminClient(bootstrap_servers=['localhost:9092'])

consumer = KafkaConsumer("EMA01", bootstrap_servers = "localhost",value_deserializer=lambda v: json.dumps(v).encode("ascii"), auto_offset_reset='earliest',)

kafka_client = KafkaClient(hosts='localhost:9092',)
kafka_topic = kafka_client.topics["EMA01"]
kafka_producer = kafka_topic.get_sync_producer()

def connect_database():
    try:
        if mongo_client.server_info():
            print(f"\nConectado ao MongoDB Server.")
            return True
    except Exception as e:
        print(f"Erro ao conectar com o MongoDB: {e}")
        return False

def disconnect_database():
    try:
        if mongo_client is not None:
            mongo_client.close()
            print("Conexão encerrada")
        else:
            print("Não há conexão ativa para encerrar.")
    except Exception as e:
        print(f"Erro ao encerrar conexão com o MongoDB: {e}")
            
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

        msg = f"\n-------------------------------------"
        msg += f"\nData atual: {data_atual}"
        msg += f"\nHora: {hora_atual}"
        msg += f"\nLatitude: {latitude}"
        msg += f"\nLongitude: {longitude}"
        msg += f"\nTemperatura: {temperatura} ºC"
        msg += f"\nPluviometro: {pluviometro} mm"
        msg += f"\nVelocidade do vento: {vel_vento} m/s"
        msg += f"\nDireção do vento: {dir_vento}º"
        msg += f"\n-------------------------------------"

        print(f"Mensagem recebida pelo tópico {topic}:{msg}\n")

        # Insere dados no mongoDB
        data = {
            "data": str(data_atual),
            "hora": hora_atual,
            "latitude": latitude,
            "longitude": longitude,
            "temperatura": temperatura,
            "pluviometro": pluviometro,
            "vel_vento": vel_vento,
            "dir_vento": dir_vento
        }
        mongo_collection.insert_one(data)

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

