import paho.mqtt.client as mqtt
import time
from pykafka import KafkaClient
from kafka import KafkaConsumer
from kafka.admin import KafkaAdminClient, NewTopic 

#Configurações do protocolo MQTT
mqtt_broker = 'mqtt.eclipseprojects.io'
mqtt_client = mqtt.Client('MQTTConsumer')
mqtt_client.connect(mqtt_broker)

#Configurações do Kafka
admin_client = KafkaAdminClient(bootstrap_servers=['localhost:9092'])

consumer = KafkaConsumer("EMA01", bootstrap_servers = "localhost",value_deserializer=lambda v: json.dumps(v).encode("ascii"), auto_offset_reset='earliest',)

kafka_client = KafkaClient(hosts='localhost:9092',)
kafka_topic = kafka_client.topics["EMA01"]
kafka_producer = kafka_topic.get_sync_producer()


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

def apresentarMsgs(topico):
    print(f"Apresentando mensagens do tópico {topico}")
    #for message in consumer:
    #    if message.topic == topico:
    #        print(message.value.decode("ascii"))
    while True:
        try:
            records = consumer.poll(60 * 1000) # timeout in millis , here set to 1 min
            record_list = []
            for tp, consumer_records in records.items():
                for consumer_record in consumer_records:
                    record_list.append(consumer_record.value)
                    print(record_list) # record_list will be list of dictionaries
        except Exception as error:
            print(error)


def on_message(client, userdata, message):
    msg_payload = str(message.payload)
    print(f"MQTT recebeu a mensagem: {msg_payload}")
    # publicando a mensagem em um tópico do kafka
    
    kafka_producer.produce(str(msg_payload).encode('ascii'))
    print(f"Kafka publicou {msg_payload} para o topico {kafka_topic}")
    time.sleep(1)

mqtt_client.subscribe('EMA01')
mqtt_client.on_message = on_message
mqtt_client.loop_forever()
apresentarMsgs('EMA01')
