import random
import json
import jsonschema
from jsonschema import validate
from paho.mqtt import client as mqtt_client
from kafka import KafkaProducer

arquivo_de_config= open('./jsons/ema01.json', encoding="utf8")
ema = json.loads(arquivo_de_config.read())

broker = 'localhost'
port = 1883
topic = 'topico'
client_id = f'python-mqtt-{random.randint(0, 100)}'
producer = KafkaProducer(bootstrap_servers='localhost:9092')

aux = ''

json_schema = {
    "$schema": "http://json-schema.org/draft-07/schema#",
    "type": "object",
    "properties": {
        "idema": {
            "type": "integer"
        },
        "data_leitura": {
            "type": "string",
            "format": "date"
        },
        "hora_leitura": {
            "type": "string",
            "format": "time"
        },
        "obrigatorio": {
            "type": "object",
            "properties": {
                "temperatura": {
                    "type": "object",
                    "properties": {
                        "unidade": {
                            "type": "string"
                        },
                        "valor": {
                            "type": "number"
                        }
                    },
                    "required": ["unidade", "valor"]
                },
                "umidade": {
                    "type": "object",
                    "properties": {
                        "unidade": {
                            "type": "string"
                        },
                        "valor": {
                            "type": "number"
                        }
                    },
                    "required": ["unidade", "valor"]
                },
                "velocidade_vento": {
                    "type": "object",
                    "properties": {
                        "unidade": {
                            "type": "string"
                        },
                        "valor": {
                            "type": "number"
                        }
                    },
                    "required": ["unidade", "valor"]
                },
                "direcao_vento": {
                    "type": "object",
                    "properties": {
                        "unidade": {
                            "type": "string"
                        },
                        "valor": {
                            "type": "number"
                        }
                    },
                    "required": ["unidade", "valor"]
                }
            },
            "required": ["temperatura", "umidade", "velocidade_vento", "direcao_vento"]
        },
        "opcional": {
            "type": "object",
            "properties": {
                "radiacao_solar": {
                    "type": "object",
                    "properties": {
                        "unidade": {
                            "type": "string"
                        },
                        "valor": {
                            "type": "number"
                        }
                    }
                },
                "pressao_atmos": {
                    "type": "object",
                    "properties": {
                        "unidade": {
                            "type": "string"
                        },
                        "valor": {
                            "type": "number"
                        }
                    }
                },
                "volume_chuva": {
                    "type": "object",
                    "properties": {
                        "unidade": {
                            "type": "string"
                        },
                        "valor": {
                            "type": "number"
                        }
                    }
                },
                "frequencia_chuva": {
                    "type": "object",
                    "properties": {
                        "unidade": {
                            "type": "string"
                        },
                        "valor": {
                            "type": "number"
                        }
                    }
                }
            }
        },
        "nao_previstos": {
            "type": "object"
        }
    },
    "required": ["idema", "data_leitura", "hora_leitura", "obrigatorio"]
}

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

def validar_mensagem(mensagem):
    try:
        validate(instance=mensagem, schema=json_schema)
        print("\nA mensagem é válida de acordo com o JSON Schema.")
        return True
    except jsonschema.exceptions.ValidationError as e:
        print(f"A mensagem não é válida de acordo com o JSON Schema: {e}")
        return False

def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
        aux = json.loads(msg.payload)
        print(f"\nMensagem recebida pelo tópico: {topic}.")
        print("================================================================================================\n")
        print(f"Mensagem recebida: {aux}")

        if (validar_mensagem(aux)):
            msg_json = json.dumps(aux).encode('utf-8')
            try:
                producer.send(ema['topico'], value=msg_json)
                print("Mensagem enviada para o tópico do Kafka.")
            except Exception as e:
                print(f"\nErro ao enviar a mensagem para o kafka: {e}")
            print("\n================================================================================================\n")
        else:
            print("Mensagem não é válida.\n")
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
