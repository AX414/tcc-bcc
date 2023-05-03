import random
import time
from datetime import date
from paho.mqtt import client as mqtt_client


broker = '192.168.1.10'
port = 1883
topic = "EMA/mqtt"
# generate client ID with pub prefix randomly
client_id = f'python-mqtt-{random.randint(0, 1000)}'

def connect_mqtt():
    def on_connect(client, userdata, flags, rc):
        if rc == 0:
            print("Conectado ao Broker!")
        else:
            print("Conexão falhou, códico: %d\n", rc)

    client = mqtt_client.Client(client_id)
    client.on_connect = on_connect
    client.connect(broker, port)
    return client


def publish(client):
    msg_count = 1
    while True:
        time.sleep(1)
        # data, hora, pluviometro, velocidade do vento, 
        # direção do vento, temperatura e umidade
        data_atual = date.today()
        hora_atual = time.strftime('%H:%M:%S', time.localtime())
        temperatura = random.randint(27, 35)
        pluviometro = random.randint(13.00, 17.00)
        vel_vento = random.randint(20, 46)
        dir_vento = random.randint(0,355)
        
        msg = f"\n-------------------------------------"
        msg+= f"\nData atual: {data_atual}"
        msg+= f"\nHora: {hora_atual}"
        msg+= f"\nTemperatura: {temperatura} ºC"
        msg+= f"\nPluviometro: {pluviometro} mm"
        msg+= f"\nVelocidade do vento: {vel_vento} m/s"
        msg+= f"\nDireção do vento: {dir_vento}º"
        msg+= f"\n-------------------------------------"
        
        # A variável mensagem é apenas para facilitar visualmente
        # Na realidade ele envia a string com os dados concatenados
        
        dados = f"{data_atual}|{hora_atual}|{temperatura}|{pluviometro}|{vel_vento}|{dir_vento}"
        
        result = client.publish(topic, dados)
        result: [0, 1]
        status = result[0]
        if status == 0:
            print(f"\nEnviando mensagem {msg_count} para o tópico {topic}:{msg}\n")
        else:
            print(f"\nO envio da mensagem para o tópico {topic} falhou.\n")
        msg_count += 1


def run():
    client = connect_mqtt()
    client.loop_start()
    publish(client)


if __name__ == '__main__':
    run()
