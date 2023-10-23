import random
import json
import time
from paho.mqtt import client as mqtt_client
from confluent_kafka import Producer, KafkaError
import signal
import sys

arquivo_de_config = open('./jsons/emas/ema01.json', encoding="utf8")
ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = 'topico'
client_id = f'python-mqtt-{random.randint(0, 100)}'

# Inicialize o producer como None
producer = None

# Conjunto para armazenar mensagens únicas
dados_armazenados = set()

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
        
        if producer is not None:
            try:
                producer.produce(ema['topico'], value=msg_json)
                producer.flush()  # Certifique-se de que a mensagem seja enviada
                print("Mensagem enviada para o tópico do Kafka.")
            except Exception as e:
                print(f"\nErro ao enviar a mensagem para o Kafka: {e}")
        else:
            # Adicione a mensagem ao conjunto de dados armazenados
            dados_armazenados.add(json.dumps(aux))
            print("Armazenando dados temporariamente.")
            
        print("\n================================================================================================\n")
    
    client.subscribe(topic, qos=2)
    client.on_message = on_message

def verificar_conexao_kafka():
    global producer  # Torna a variável producer global
    while True:
        if producer is None:
            try:
                # Tente criar o producer
                producer = Producer({'bootstrap.servers': 'localhost:9092'})
                print("KafkaProducer inicializado.")
                # Envie mensagens armazenadas assim que a conexão for estabelecida
                enviar_dados_armazenados()
            except Exception as e:
                print(f"Erro ao criar o KafkaProducer: {e}")
        
        producer.poll(0)  # Verifique a conexão Kafka

def enviar_dados_armazenados():
    for dado in dados_armazenados:
        msg_json = dado.encode('utf-8')
        
        if producer is not None:
            try:
                producer.produce(ema['topico'], value=msg_json)
                producer.flush()  # Certifique-se de que a mensagem seja enviada
                dados_armazenados.remove(dado)
                print("Dados armazenados enviados para o tópico do Kafka.")
            except Exception as e:
                print(f"Erro ao enviar dados armazenados para o Kafka: {e}")

def signal_handler(sig, frame):
    global producer
    print("Encerrando...")
    if producer is not None:
        producer.flush()
    sys.exit(0)

def run():
    client = connect_mqtt()
    subscribe(client)
    
    # Configure o tratamento de sinal para encerrar o programa
    signal.signal(signal.SIGINT, signal_handler)
    
    while True:
        try:
            client.loop(timeout=1.0)
        except Exception as e:
            print(f"Erro na conexão MQTT: {e}")
    
if __name__ == '__main__':
    run()
