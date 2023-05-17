#import paho.mqtt.client as mqtt
#from pykafka import KafkaClient
#from random import uniform
from kafka import KafkaConsumer
from kafka.admin import KafkaAdminClient, NewTopic
#import time

#mqtt_broker = 'mqtt.eclipseprojects.io'
#mqtt_client = mqttClient('MQTTProducer')
#mqtt_client.connect(mqtt_broker)


admin_client = KafkaAdminClient(bootstrap_servers=['localhost:9092'])

#kafka_client = KafkaClient(hosts='localhost:9092')
#kafka_topic = kafka_client.topics["EMA01/dados"]
#kafka_producer = kafka_topic.get_sync_producer()


def ver_topicos():
    topicos_existentes = consumer.topics()
    print(list(topicos_existentes))

def create_topic(topico):
    topicos_existentes = consumer.topics()
    print(list(topicos_existentes))
    lista_topicos = []
    if topico not in topicos_existentes:
        print(f'Topico: {topico} adicionado na lista.')
        lista_topicos.append(NewTopic(name=topico, num_partitions=1, replication_factor=1))
    else:
        print('Este tópico já existe na lista')
    try:
        if lista_topicos:
            admin_client.create_topics(new_topics = lista_topicos, validate_only=False,)
            print("Tópico criado com sucesso")
        else:
            print("Tópico já existe")
    except TopicAlreadyExistsError as error:
        print(error)
    except Exception as error:
        print(error)

def delete_topic(topico):
    try:
        admin_client.delete_topics(topics=topic_names)
        print("Tópico deletado.")
    except UnknowTopicOrPartitionError as error:
        print(error)
    except Exception as error:
        print(error)

consumer = KafkaConsumer(bootstrap_servers = "localhost",)
ver_topicos()
create_topic("EMA01")
ver_topicos()
#while True:
#    randNumber = uniform(20.0,21.0)
#    mqtt_client.publish(f"TEMP: {randNumber}")
#    print(f"MQTT publicou {randNumber} para o topico {kafka_topic}")
#
#    kafka_producer.produce(str(randNumber)).encode('ascii'))
#    print("Kafka publicou {randNumber} para o topico {kafka_topic}")
#    time.sleep(1)
