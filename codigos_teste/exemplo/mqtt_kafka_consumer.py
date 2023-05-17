import paho.mqtt.client as mqtt
from pykafka import KafkaClient
from random import uniform 
import time

mqtt_broker = 'mqtt.eclipseprojects.io'
mqtt_client = mqtt.Client('MQTTConsumer')
mqtt_client.connect(mqtt_broker)


def on_message(client, userdata, message):
    msg_payload = str(message.payload)
    print(f"MQTT recebeu a mensagem: {msg_payload}")

mqtt_client.loop_start()
mqtt_client.subscribe('EMA01')
mqtt_client.on_message = on_message
time.sleep(1)
mqtt_client.loop_stop()

