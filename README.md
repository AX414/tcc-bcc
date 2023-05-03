# EMA simulada utilizando protocolo MQTT e Mosquitto:
Um projeto de uma estação meteorológica automática simulada utilizando o protocolo mqtt e o broker mosquitto. Vale ressaltar que utilizei o Linux durante seu desenvolvimento (porém é possível utilizar o windows também), por isso os comandos a seguir são do linux.

## Instalações necessárias:
- sudo apt-get install mosquitto
- sudo apt-get install mosquitto-clients
- pip install geopy

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
OBS.: Aconselho ligar e desligar o serviço do mosquito para toda configuração efetuada aqui, inclusive logo após sua instalação com:
sudo service mosquitto stop -> sudo service mosquitto start -> sudo service mosquitto status 

# Como funciona:
Os códigos funcionam da seguinte forma, o pub.py envia a mensagem utilizando o protocolo MQTT, nele, é indicado o broker, o endereço para onde a mensagem está sendo enviada, neste caso, pode ser o ip de onde o "mosquitto" está instalado. O código de sub receberá a mensagem por meio do protocolo, nele, o endereço do broker será "localhost" pois é nela onde o mosquitto se encontrará instalado, este código pode ser utilizado entre dispositivos, como por exemplo uma placa de raspberry pi.
