import random
import time
from datetime import date
from queue import Queue
from paho.mqtt import client as mqtt_client
from geopy.geocoders import Nominatim
from pymongo import MongoClient

broker = 'localhost'
port = 1883
topic = "EMA01"
mongo_uri = "mongodb://localhost:27017/"
database_name = "awsmqtt"
collection_name = "relatorios"
geolocator = Nominatim(user_agent="geolocalização")

client_id = f'python-mqtt-{random.randint(0, 1000)}'
fila = Queue()

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

def connect_mongo():
    try:
        client = MongoClient(mongo_uri)
        db = client[database_name]
        collection = db[collection_name]
        return collection
    except Exception as error:
        print(f"Error connecting to MongoDB: {error}")

def captar_Dados():
    data_atual = date.today()
    hora_atual = time.strftime('%H:%M:%S', time.localtime())

    try:
        location = geolocator.geocode("R. José Ramos Júnior, 27-50 - Jardim Tropical, Presidente Epitácio - SP, 19470-000")
        latitude = location.latitude
        longitude = location.longitude
    except AttributeError:
        latitude = 0
        longitude = 0
        print("Problema com os dados ou uma falha com o Geocode.")
    except GeocoderTimedOut:
        latitude = 0
        longitude = 0
        print("Um erro de timeout ocorreu.")

    temperatura = random.randint(27, 35)
    pluviometro = random.uniform(13.00, 17.00)
    vel_vento = random.uniform(20, 46)
    dir_vento = random.randint(0, 355)

    dados = {
        "data": data_atual.isoformat(),
        "hora": hora_atual,
        "latitude": latitude,
        "longitude": longitude,
        "temperatura": temperatura,
        "pluviometro": pluviometro,
        "vel_vento": vel_vento,
        "dir_vento": dir_vento,
    }

    return dados

def enviar_fila(collection):
    if collection is None:
        time.sleep(1)
        dados = captar_Dados()
        fila.put(dados)
        print(f"\nArmazenando mensagem para posteriormente enviar ao tópico {topic}:{dados}\n")
        return fila

def publicar_dado_atual(collection):
    while True:
        time.sleep(1)
        dados = captar_Dados()
        result = collection.insert_one(dados)
        if result.inserted_id:
            print(f"\nEnviando a mensagem para o tópico {topic}:{dados}\n")
        else:
            print(f"\nO envio da mensagem para o tópico {topic} falhou.\n")
            return

def publish(collection):
    if collection is not None:
        publicar_dado_atual(collection)

def run():
    client = connect_mqtt()
    collection = connect_mongo()
    while True:
        if collection is not None:
            client.loop_start()
            publish(collection)

if __name__ == '__main__':
    run()

