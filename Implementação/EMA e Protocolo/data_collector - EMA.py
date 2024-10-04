import json
import random
import time
from queue import Queue
from datetime import date
from paho.mqtt import client as mqtt_client
from time import sleep

# Carregando as configurações do arquivo JSON
with open('./jsons/emas/ema02.json', encoding="utf8") as arquivo_de_config:
    ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = 'topico'
client_id = f'python-mqtt-{random.randint(0, 1000)}'

def connect_mqtt():
    def on_connect(client, userdata, flags, reason_code, properties=None):
        if reason_code == 0:
            print("Conectado ao Broker!")
        else:
            print(f"Conexão falhou, código: {reason_code}")

    try:
        # Atualizando para usar a versão da API de callback
        client = mqtt_client.Client(mqtt_client.CallbackAPIVersion.VERSION2)
        client.on_connect = on_connect
        client.connect(broker, port)
        return client
    except Exception as error:
        print(f"Erro ao conectar: {error}")

def captar_dados():
    # Preparando dados simulados
    data_atual = date.today()
    hora_atual = time.strftime('%H:%M:%S', time.localtime())

    latitude = ema['latitude']
    longitude = ema['longitude']

    # Dados obrigatórios
    temperatura = round(random.uniform(27.0, 46.0), 2)
    unidade_tem = "°C"
    umidade = round(random.uniform(47.0, 101.0), 2)
    unidade_um = "%"
    vento_velocidade = round(random.uniform(1.0, 51.0), 2)
    unidade_vv = "m/s"
    vento_direcao = random.randint(0, 360)
    unidade_vd = "graus"

    # Dados opcionais
    radiacao_solar = round(random.uniform(100.0, 1501.0), 2)
    unidade_rs = "W/m²"
    pressao_atmos = round(random.uniform(970.0, 1101.0), 2)
    unidade_pa = "hPa"
    volume_chuva = round(random.uniform(0.0, 101.0), 2)
    unidade_vc = "mm"
    frequencia_chuva = round(random.uniform(0.0, 101.0), 2)
    unidade_fc = "mm/h"

    status_ema = 'Online'
    carga_bateria = round(random.uniform(0.0, 100.0), 2)

    dias = random.randint(0, 10)
    horas = random.randint(1, 24)
    minutos = random.randint(1, 60)
    # Formatar a string de uptime
    uptime = f'{dias} dia(s), {horas} hora(s), {minutos} min'

    # Criar a estrutura JSON
    dados_json = {
        "observacao": {
            "topico": ema['topico'],
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
            "observacoes_nao_previstas": {}
        },
        "diagnostico": {
            "status_ema": status_ema,
            "carga_bateria": carga_bateria,
            "uptime": uptime,
            "diagnosticos_nao_previstos": {}
        }
    }

    # Converte a estrutura JSON em uma string JSON
    return json.dumps(dados_json)

def publicar_dado_atual(client):
    while True:
        time.sleep(3)
        # Captando os dados atuais
        dados = captar_dados()
        result = client.publish(topic, dados, qos=2)
        status = result.rc
        if status == mqtt_client.MQTT_ERR_SUCCESS:
            print(f"\nEnviando a mensagem para o tópico {topic}:\n\n{dados}\n")
        else:
            print(f"\nO envio da mensagem para o tópico {topic} falhou.\n")

def run():
    while True:
        client = connect_mqtt()
        if client is not None:
            client.loop_start()
            publicar_dado_atual(client)

if __name__ == '__main__':
    run()
