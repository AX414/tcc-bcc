# Desenvolvimento e implementação de um sistema distribuído aberto para gerenciamento de estações meteorológicas
Este é o repositório do meu Trabalho de Conclusão de Curso que realizei durante meu curso de Bacharelado de Ciência da Computação. Nele é possível ver alguns exemplos em python de uma arquitetura que desenvolvi que é utilizada para tratar dos dados recebidos de estações meteorológicas automáticas, a pasta contém alguns exemplos em python e um sistema feito em PHP. Eu utilizei o protocolo MQTT, o broker Mosquitto e o Kafka durante este desenvolvimento. A primeiro momento eu desenvolvi em um sistema Linux e posteriormente em um Windows, devido a isso, segue em anexo sobre a configuração e instalação que realizei nestes sistemas durante os meus testes.

#Linux:

## 1. Instalações necessárias para o teste:
Estes comandos devem ser executados no terminal do Linux, vale ressaltar que a minha máquina possui o ```Python v3.10.6``` e o ```pip v23.0.1```.
- sudo apt-get install mosquitto
- sudo apt-get install mosquitto-clients
- pip install paho-mqtt mysql-connector-python geopy pykafka kafka-python pymongo

## 2. Configurações do mosquitto.conf:
Após instalar o broker ```Mosquitto```, é necessário configurar ele, geralmente ele ficará localizado na pasta ```etc```, porém, se não encontrá-lo, utilize o comando ```whereis mosquitto```, este comando deve ajudar a encontrar a pasta do broker baixado. Dentro da pasta dele, deve haver um arquivo de configuração chamado ```conf.d```, altere ele para que ele se assemelhe ao conteúdo abaixo.

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
sudo service mosquitto stop -> sudo service mosquitto start -> sudo service mosquitto status.

## 3. Comandos para rodar o Kafka no Linux:
É necessário estar na pasta do kafka que foi baixado, no meu caso utilizei o kafka 3.4.0, você pode baixá-lo a partir de [aqui](https://kafka.apache.org/downloads). Após isso, extraia o arquivo e dentro da pasta do kafka que foi baixado, utilize estes comandos:

Entre como super usuário: ```sudo su```

Inicializar o zookeeper: ```bin/zookeeper-server-start.sh config/zookeeper.properties```.

Inicializar o kafka: ```bin/kafka-server-start.sh config/server.properties```.

Para visualizar as mensagens que chegam em um tópico do kafka e apresentar todas as mensagens deste tópico: ```bin/kafka-console-consumer.sh --bootstrap-server localhost:9092 --topic nome_do_topico --from-beginning```.

#Windows:

## 1. Como rodar o Mosquitto no Windows:
Após sua instalação, vá até sua pasta e execute cada um desses comandos para testar:

```
Inicializar o sub: mosquito_sub -t topico -h localhost

Inicializar o pub e enviar a mensagem por um outro terminal: mosquito_pub -t topico -h localhost -m "temperatura: 30"
```

## 2. Como rodar o Kafka no Windows:
Após sua instalação, vá até sua pasta e execute cada um desses comandos para testar:

```
Iniciar zookeeper: zookeeper-server-start.bat ..\..\config\zookeeper.properties

Iniciar servidor kafka: kafka-server-start.bat ..\..\config\server.properties

Criar um tópico no kafka: kafka-topics.bat --create --topic my-topic --bootstrap-server localhost:9092 --replication-factor 1 --partitions 3

Enviar mensagem para o tópico: kafka-console-producer.bat --broker-list localhost:9092 --topic my-topic

Visualizar mensagens do tópico: kafka-console-consumer.bat --bootstrap-server localhost:9092 --topic my-topic --from-beginning
```
