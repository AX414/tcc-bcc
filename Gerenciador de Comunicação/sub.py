import random
import re
import json
import jsonschema
from jsonschema import validate
import mysql.connector
from mysql.connector import Error
from datetime import date, time
from paho.mqtt import client as mqtt_client

broker = 'localhost'
port = 1883
topic ="morrigan1"
# generate client ID with pub prefix randomly
client_id = f'python-mqtt-{random.randint(0, 100)}'

connection = mysql.connector.connect(host='localhost',database='awsmqtt', user='root', password='ifsp')

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


def connect_database():
    try:
        if connection.is_connected():
            db_info = connection.get_server_info()
            print(f"\nConectado ao MySQL Server {db_info}")
            cursor = connection.cursor()
            cursor.execute("select database();")
            record = cursor.fetchone()
            print(f"Conectado ao banco de dados {record}")
    except Exception as e:
        print(f"Erro: {e}")

def disconnect_database():
    cursor = connection.cursor()
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("Conexão encerrada")

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
        print(f"\nA mensagem não é válida de acordo com o JSON Schema: {e}")
        return False

def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
        cursor = connection.cursor()

	    # Pegando os dados da mensagem recebida e separando eles
        aux = json.loads(msg.payload)
        print(f"\n================================================\nMensagem recebida: {aux}")

        if (validar_mensagem(aux)):
            print(f"Mensagem recebida pelo tópico {topic}.\n================================================\n")
        else:
            print("Mensagem não é válida.\n")
        
    client.subscribe(topic, qos=0)
    client.on_message = on_message
    
    
def run():
    client = connect_mqtt()
    connect_database()
    subscribe(client)
    client.loop_forever()
    
if __name__ == '__main__':
    run()
