import random
import json
import time
from paho.mqtt import client as mqtt_client
from kafka import KafkaProducer, errors

arquivo_de_config = open('./jsons/emas/ema02.json', encoding="utf8")
ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = 'topico'
client_id = f'python-mqtt-{random.randint(0, 100)}'
producer = None

def connect_kafka():
    while True:
        try:
            producer = KafkaProducer(bootstrap_servers='localhost:9092')
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
    # Usando a versão mais recente do callback (com 'properties')
    def on_connect(client, userdata, flags, reason_code, properties=None):
        if reason_code == 0:
            print("Conectado ao Broker MQTT!")
        else:
            print(f"Conexão falhou, código: {reason_code}")

    try:
        # Criando o cliente MQTT sem o uso da versão deprecated
        client = mqtt_client.Client(mqtt_client.CallbackAPIVersion.VERSION2)
        client.on_connect = on_connect
        client.connect(broker, port)
        return client
    except Exception as error:
        print(f"Erro ao conectar ao MQTT: {error}")
        return None

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
    if client:  # Verifica se o cliente MQTT foi criado com sucesso
        subscribe(client, producer)
        client.loop_forever()
    else:
        print("Não foi possível conectar ao broker MQTT.")

if __name__ == '__main__':
    run()
