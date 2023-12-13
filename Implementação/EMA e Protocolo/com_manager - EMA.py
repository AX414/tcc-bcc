import random
import json
import time
from paho.mqtt import client as mqtt_client
from kafka import KafkaProducer, errors

arquivo_de_config = open('./jsons/emas/ema01.json', encoding="utf8")
ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = 'topico'
client_id = f'python-mqtt-{random.randint(0, 100)}'
producer = None

def connect_kafka():
    while True:
        try:
            producer = KafkaProducer(bootstrap_servers='192.168.1.6:9092')
            print("Conectado ao Broker Kafka!")
            return producer
        except errors.NoBrokersAvailable:
            print("Nenhum broker disponível. Tentando reconectar em 1 segundo...")
            time.sleep(1)

def publish_message(producer, msg):
    try:
        producer.send(ema['topico'], value=msg)
        print("Mensagem enviada para o tópico do Kafka.")
    except Exception as e:
        print(f"\nErro ao enviar a mensagem para o Kafka: {e}")

def connect_mqtt():
    def on_connect(client, userdata, flags, rc):
        if rc == 0:
            print("Conectado ao Broker MQTT!")
        else:
            print("Conexão falhou, código: %d\n", rc)
    try:
        client = mqtt_client.Client(client_id, clean_session=True)
        client.on_connect = on_connect
        client.connect(broker, port)
        return client
    except Exception as error:
        print(f"Erro: {error}")

def subscribe(client: mqtt_client, producer):
    def on_message(client, userdata, msg):
        nonlocal producer
        aux = json.loads(msg.payload)
        print(f"\nMensagem recebida pelo tópico: {topic}.")
        print("================================================================================================\n")
        print(f"Mensagem recebida: {aux}")

        msg_json = json.dumps(aux).encode('utf-8')
        try:
            publish_message(producer, msg_json)
        except errors.NoBrokersAvailable:
            print("Broker Kafka não disponível. Tentando reconectar em 1 segundo...")
            producer = connect_kafka()

        print("\n================================================================================================\n")

    client.subscribe(topic, qos=2)
    client.on_message = on_message

def run():
    global producer
    producer = connect_kafka()

    client = connect_mqtt()
    subscribe(client, producer)
    client.loop_forever()

if __name__ == '__main__':
    run()
