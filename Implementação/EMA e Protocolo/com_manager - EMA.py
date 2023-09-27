import random
import json
from paho.mqtt import client as mqtt_client
from kafka import KafkaProducer

arquivo_de_config= open('./jsons/ema01.json', encoding="utf8")
ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = 'topico'
client_id = f'python-mqtt-{random.randint(0, 100)}'
producer = KafkaProducer(bootstrap_servers='localhost:9092')

def connect_mqtt():
    def on_connect(client, userdata, flags, rc):
        if rc == 0:
            print("Conectado ao Broker!")
        else:
            print("Conexão falhou, código: %d\n", rc)
    try:
        client = mqtt_client.Client(client_id, clean_session=True)
        client.on_connect = on_connect
        client.connect(broker, port)
        return client
    except Exception as error:
        print(f"Erro: {error}")

def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
        aux = json.loads(msg.payload)
        print(f"\nMensagem recebida pelo tópico: {topic}.")
        print("================================================================================================\n")
        print(f"Mensagem recebida: {aux}")

        msg_json = json.dumps(aux).encode('utf-8')
        try:
            producer.send(ema['topico'], value=msg_json)
            print("Mensagem enviada para o tópico do Kafka.")
        except Exception as e:
            print(f"\nErro ao enviar a mensagem para o kafka: {e}")
        print("\n================================================================================================\n")
    
    client.subscribe(topic, qos=1)
    client.on_message = on_message
    

def run():
    client = connect_mqtt()
    subscribe(client)
    client.loop_forever()
    producer.close()
    
if __name__ == '__main__':
    run()
