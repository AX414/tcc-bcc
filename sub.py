import random
import re
from datetime import date
from datetime import time
from paho.mqtt import client as mqtt_client


broker = 'localhost'
port = 1883
topic = "EMA/mqtt"
# generate client ID with pub prefix randomly
client_id = f'python-mqtt-{random.randint(0, 100)}'

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


def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
    
	# Pegando os dados da mensagem recebida e separando eles
	# para apresentar na mensagem formatada
        dados = re.findall(r"[\w']+", msg.payload.decode())
        data_atual = date(int(dados[0]),int(dados[1]),int(dados[2]))       
        hora_atual = time(int(dados[3]),int(dados[4]),int(dados[5]))
        temperatura = dados[6]
        pluviometro = dados[7]
        vel_vento = dados[8]
        dir_vento = dados[9]
        # Apresentando os dados
        # print(dados)
	
        msg = f"\n-------------------------------------"
        msg+= f"\nData atual: {data_atual}"
        msg+= f"\nHora: {hora_atual}"
        msg+= f"\nTemperatura: {temperatura} ºC"
        msg+= f"\nPluviometro: {pluviometro} mm"
        msg+= f"\nVelocidade do vento: {vel_vento} m/s"
        msg+= f"\nDireção do vento: {dir_vento}º"
        msg+= f"\n-------------------------------------"

        print(f"\nMensagem recebida pelo tópico {topic}:{msg}\n")

    client.subscribe(topic)
    client.on_message = on_message


def run():
    client = connect_mqtt()
    subscribe(client)
    client.loop_forever()


if __name__ == '__main__':
    run()
