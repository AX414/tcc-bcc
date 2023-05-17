# Estação Meteorológica Automática (EMA) simulada utilizando protocolo MQTT, broker Mosquitto e Kafka:
Um projeto de uma estação meteorológica automática simulada utilizando o protocolo mqtt, o broker Mosquitto e o Kafka. Vale ressaltar que utilizei o ```Linux(Ubuntu v22.04)``` durante seu desenvolvimento (porém é possível utilizar o windows também).

# 1. Instalações necessárias:
Estes comandos devem ser executados no terminal do Linux, vale ressaltar que a minha máquina possui o ```Python v3.10.6``` e o ```pip v23.0.1```.
- sudo apt-get install mosquitto
- sudo apt-get install mosquitto-clients
- pip install mysql-connector-python
- pip install geopy
- pip install kafka-python
- pip install pykafka

# 2. Configurações do mosquitto.conf:
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

# 3. Comandos para rodar o Kafka:
É necessário estar na pasta do kafka que foi baixado, no meu caso utilizei o kafka 3.4.0, você pode baixá-lo a partir de [aqui](https://kafka.apache.org/downloads). Após isso, extraia o arquivo e dentro da pasta do kafka que foi baixado, utilize estes comandos:

Inicializar o zookeeper: ```bin/zookeeper-server-start.sh config/zookeeper.properties```.

Inicializar o kafka: ```bin/kafka-server-start.sh config/server.properties```.

Para visualizar as mensagens que chegam em um tópico do kafka e apresentar todas as mensagens deste tópico: ```bin/kafka-console-consumer.sh --bootstrap-server localhost:9092 --topic nome_do_topico --from-beginning```.


# 4. Como os programas funcionam:
A seguir eu explico como cada um dos programas funcionam, como é possível ver eu tenho 2 exemplos até o momento, um é o caso de publisher/subscriber com o uso do MQTT e o outro exemplo é o exato mesmo caso, porém após o protocolo receber a mensagem, ele irá enviar a um tópico do kafka.

## 4.1. Exemplo 1 - pub.py e sub.py:
Estes dois códigos foram códigos de teste para realizar a comunicação entre máquinas por meio do protocolo MQTT, os testes ocorreram conforme o esperado e pude fazer com que dois dispositivos da mesma rede, se comunicassem, no caso, um seria o ```publisher``` e o outro foi o ```subscriber```.

O código do arquivo ```pub.py``` envia a mensagem utilizando o protocolo MQTT, nele, é indicado o broker (o endereço IP para onde a mensagem está sendo enviada) neste caso, pode ser o IP de onde o "Mosquitto" está instalado. 

O código do arquivo ```sub.py``` receberá a mensagem por meio do protocolo, nele, o endereço do broker será "localhost" pois é nela onde o mosquitto se encontrará instalado, este código pode ser utilizado entre dispositivos, como por exemplo uma placa de raspberry pi.

Caso o ```Mosquitto``` estiver na mesma máquina onde os arquivos estejam, pode simplesmente colocar o endereços do ```pub.py``` e do ```sub.py``` como "localhost". 

## 4.2. Exemplo 2 - mqtt_kafka_producer.py e mqtt_kafka_consumer.py:
Devido ao teste dos últimos dois códigos ter sido um sucesso, conforme o solicitado pelo meu orientador, eu integrei o Kafka aos programas, para que após o MQTT publicasse e assinasse uma mensagem, o Kafka iria inserir o dado em um de seus tópicos.

Estes dois códigos são um teste de integração que estou efetuando, basicamente funcionam da mesma forma que os códigos de pub e sub, porém são menores devido a serem apenas um teste.

O código do arquivo ```mqtt_kafka_producer.py``` irá publicar mensagens em um tópico especificado nele, ele irá utilizar o Mosquitto como broker.

O código do arquivo ```mqtt_kafka_consumer.py``` irá assinar as mensagens do MQTT e publicá-las para um tópico no kafka.
