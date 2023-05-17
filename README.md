# Estação Meteorológica Automática (EMA) simulada utilizando protocolo MQTT, broker Mosquitto e Kafka:
Um projeto de uma estação meteorológica automática simulada utilizando o protocolo mqtt, o broker mosquitto e o Kafka. Vale ressaltar que utilizei o Linux durante seu desenvolvimento (porém é possível utilizar o windows também), por isso os comandos a seguir são do linux.

## Instalações necessárias:
- sudo apt-get install mosquitto
- sudo apt-get install mosquitto-clients
- pip install mysql-connector-python
- pip install geopy
- pip install kafka-python

## Configurações do mosquitto.conf:
```
# Place your local configuration in /etc/mosquitto/conf.d/
#
# A full description of the configuration file is at
# /usr/share/doc/mosquitto/examples/mosquitto.conf.example

persistence true
persistence_location /var/lib/mosquitto/

log_dest file /var/log/mosquitto/mosquitto.log

include_dir /etc/mosquitto/conf.d

allow_anonymous true
listener 1883
```

## Comandos para rodar o kafka:
É necessário estar na pasta do kafka que foi baixado, no meu caso utilizei o kafka 3.4.0, você pode baixá-lo a partir de [aqui](https://kafka.apache.org/downloads). Após isso, extraia o arquivo e dentro da pasta do kafka que foi baixado, utilize estes comandos:

Inicializar o zookeeper: ```bin/zookeeper-server-start.sh config/zookeeper.properties```

Inicializar o kafka: ```bin/kafka-server-start.sh config/server.properties```

Para visualizar as mensagens que chegam em um tópico do kafka e apresentar todas as mensagens deste tópico: ```bin/kafka-console-consumer.sh --bootstrap-server localhost:9092 --topic nome_do_topico --from-beginning```

OBS.: Aconselho ligar e desligar o serviço do mosquito para toda configuração efetuada aqui, inclusive logo após sua instalação com:
sudo service mosquitto stop -> sudo service mosquitto start -> sudo service mosquitto status 

# Como funciona:
Os códigos funcionam da seguinte forma, o pub.py envia a mensagem utilizando o protocolo MQTT, nele, é indicado o broker, o endereço para onde a mensagem está sendo enviada, neste caso, pode ser o ip de onde o "mosquitto" está instalado. O código de sub receberá a mensagem por meio do protocolo, nele, o endereço do broker será "localhost" pois é nela onde o mosquitto se encontrará instalado, este código pode ser utilizado entre dispositivos, como por exemplo uma placa de raspberry pi.
