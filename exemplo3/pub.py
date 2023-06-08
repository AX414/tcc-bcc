import random
import time
from queue import Queue
from datetime import date
from paho.mqtt import client as mqtt_client
from geopy.geocoders import Nominatim
from pykafka import KafkaClient

broker = 'localhost'
port = 1883
topic = "EMA01"
geolocator = Nominatim(user_agent="geolocalização")
# generate client ID with pub prefix randomly
client_id = f'python-mqtt-{random.randint(0, 1000)}'
fila = Queue()

def connect_mqtt():
    def on_connect(client, userdata, flags, rc):
        if rc == 0:
            print("Conectado ao Broker!")
        else:
            print("Conexão falhou, código: %d\n", rc)
    try:
        client = mqtt_client.Client(client_id)
        client.on_connect = on_connect
        client.connect(broker, port)
        return client
    except Exception as error:
        print(f"Erro: {error}");
    
def captar_Dados():
    # Preparando dados simulados
    data_atual = date.today()
    hora_atual = time.strftime('%H:%M:%S', time.localtime())

    # Localização por Latitude e Longitude
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
    pluviometro = random.randint(13.00, 17.00)
    vel_vento = random.randint(20, 46)
    dir_vento = random.randint(0,355)

    msg = f"\n-------------------------------------"
    msg+= f"\nData atual: {data_atual}"
    msg+= f"\nHora atual: {hora_atual}"
    msg+= f"\nLatitude: {latitude}"
    msg+= f"\nLongitude: {longitude}"
    msg+= f"\nTemperatura: {temperatura} ºC"
    msg+= f"\nPluviometro: {pluviometro} mm"
    msg+= f"\nVelocidade do vento: {vel_vento} m/s"
    msg+= f"\nDireção do vento: {dir_vento}º"
    msg+= f"\n-------------------------------------"
        
    # A variável mensagem é apenas para facilitar visualmente
    # Na realidade ele envia a string com os dados concatenados 
    # na lista dos dados que criei
    dados = []
    dados.append(msg)
    dados.append(f"{data_atual},{hora_atual},{latitude},{longitude},{temperatura},{pluviometro},{vel_vento},{dir_vento}")
    return dados
    
def enviar_fila(client):
    if(client is None):
        time.sleep(1)
        dados = captar_Dados()
        aux = dados[1].split(',')
        fila.put(dados)
        print(f"\nArmazenando mensagem de {aux[1]}  para posteriormente enviar ao tópico {topic}:{dados[0]}\n")
        return fila     
            
def publicar_dado_atual(client):
    while True:
        time.sleep(1)
        # Captando os dados atuais
        dados = captar_Dados()
        aux = dados[1].split(',')
        result = client.publish(topic, dados[1], qos=0, retain=True)
        result: [0, 1]
        status = result[0]
        if status == 0:
            print(f"\nEnviando a mensagem de {aux[1]} para o tópico {topic}:{dados[0]}\n")
        else:
            print(f"\nO envio da mensagem para o tópico {topic} falhou.\n")
            return
            
def publish(client):
    if(client is not None):
        publicar_dado_atual(client)

def run():
    while True:
        client = connect_mqtt()
        if(client is not None):
            client.loop_start()
            publish(client)

if __name__ == '__main__':
    run()
