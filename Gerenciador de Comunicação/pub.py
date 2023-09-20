import random
import time
from queue import Queue
from datetime import date
from paho.mqtt import client as mqtt_client
from geopy.geocoders import Nominatim
import json


### Dados da REDEMET pela API? PP, Maringá, Londrina, Bauru

broker = 'localhost'
port = 1883
topic = "morrigan1"
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

    # Obrigatórios
    temperatura = round(random.uniform(27.0, 35.0), 2)
    unidade_tem = "°C"
    umidade = round(random.uniform(47.0, 55.0), 2)
    unidade_um = "%"
    vento_velocidade = round(random.uniform(1.0, 10.0), 2)
    unidade_vv = "m/s"
    vento_direcao = random.randint(0, 360)
    unidade_vd = "graus"

    # Opcionais
    radiacao_solar = round(random.uniform(100.0, 1000.0), 2)
    unidade_rs = "W/m²"
    pressao_atmos = round(random.uniform(970.0, 1030.0), 2)
    unidade_pa = "hPa"
    volume_chuva = round(random.uniform(0.0, 20.0), 2)
    unidade_vc = "mm"
    frequencia_chuva = round(random.uniform(0.0, 50.0), 2)
    unidade_fc = "mm/h"

    # Criar a estrutura JSON
    dados_json = {
        "idema": 0,
        "data_leitura": str(data_atual),
        "hora_leitura": hora_atual,
        "obrigatorio": {
            "temperatura": {
                "unidade": unidade_tem,
                "valor": temperatura
            },
            "umidade": {
                "unidade": unidade_um,
                "valor": umidade
            },
            "velocidade_vento": {
                "unidade": unidade_vv,
                "valor": vento_velocidade
            },
            "direcao_vento": {
                "unidade": unidade_vd,
                "valor": vento_direcao
            }
        },
        "opcional": {
            "radiacao_solar": {
                "unidade": unidade_rs,
                "valor": radiacao_solar
            },
            "pressao_atmos": {
                "unidade": unidade_pa,
                "valor": pressao_atmos
            },
            "volume_chuva": {
                "unidade": unidade_vc,
                "valor": volume_chuva
            },
            "frequencia_chuva": {
                "unidade": unidade_fc,
                "valor": frequencia_chuva
            }
        },
        "nao_previstos": {}
    }

    # Converte a estrutura JSON em uma string JSON
    msg = json.dumps(dados_json)

    # Retorna a string JSON
    return msg

def enviar_fila(client):
    if(client is None):
        time.sleep(1)
        dados = captar_Dados()
        fila.put(dados)
        print(f"\nArmazenando mensagem para posteriormente enviar ao tópico {topic}:\n\n{dados}\n")
        return fila

def publicar_dado_atual(client):
    while True:
        time.sleep(1)
        # Captando os dados atuais
        dados = captar_Dados()
        result = client.publish(topic, dados, qos=1, retain=True)
        result: [0, 1]
        status = result[0]
        if status == 0:
            print(f"\nEnviando a mensagem para o tópico {topic}:\n\n{dados}\n")
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
