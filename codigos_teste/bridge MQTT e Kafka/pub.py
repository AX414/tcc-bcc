import paho.mqtt.client as mqtt
from random import uniform
import time

mqtt_broker = "localhost"
mqtt_client = mqtt.Client("Temperature_Inside")
mqtt_client.connect(mqtt_broker)

while True:
    randNumber = uniform(20.0, 21.0)
    mqtt_client.publish("temperature2", randNumber)
    print("MQTT: Just published " + str(randNumber) + " to topic temperature2")
    time.sleep(3)
