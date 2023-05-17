import paho.mqtt.client as mqtt
import time
import random
from pykafka import KafkaClient
from kafka import KafkaConsumer
from kafka.admin import KafkaAdminClient, NewTopic


mqtt_broker = 'mqtt.eclipseprojects.io'
mqtt_client = mqtt.Client('MQTTProducer')
mqtt_client.connect(mqtt_broker)
topic = "EMA01"


def publicar():
    while True:
        i = 0
        while i<2:
            randNumber = random.randint(20,28)
            mqtt_client.publish("EMA01",randNumber)
            print("========================================================")
            print(f"MQTT publicou {randNumber} para o topico {topic}")
            i = i + 1
        if i ==2:
            break

publicar()
