<html>

<h1>Desenvolvimento e implementação de um sistema distribuído aberto para gerenciamento de estações meteorológicas</h1>

> O repositório a seguir consta com meu Trabalho de Conclusão de Curso de Bacharelado de Ciência da Computação 👨‍💻. O repositório está dividido em 3 partes: <a href="https://github.com/AX414/tcc-bcc/tree/main/Artigos">Artigos</a>, <a href="https://github.com/AX414/tcc-bcc/tree/main/Implementação">Implementação</a> e <a href="https://github.com/AX414/tcc-bcc/tree/main/Kafka">Kafka</a>.

<details>
<summary><b>Sobre o trabalho</b></summary>

Devido às constantes mudanças climáticas e do tempo, o monitoramento das variáveis meteorológicas para o estudo sobre o efeito dessas alterações climáticas se tornou necessário para elaboração de ações preditivas, adaptativas e corretivas. Nesse contexto, são utilizadas diversas abordagens, sendo o uso de imagens de satélites o método mais amplo e conhecido, permitindo a análise de massas de ar, temperatura e a possibilidade de chuvas. Contudo, para um monitoramento mais preciso de cada região, podem ser utilizado diversas estações meteorológicas automáticas (EMAs), que além de oferecer as variáveis climáticas já citadas, podem fornecer dados sobre materiais particulados como a fuligem, dióxido e monóxido de carbono, direção e velocidade do vento, radiação solar, entre outros.

Nesse sentido, este trabalho tem como objetivo desenvolver e implementar um sistemade informação distribuído e aberto para gerenciamento de estações meteorológicas automáticas (EMA) de forma a facilitar o acesso aos dados coletados por essas estações. Os objetivos específicos deste trabalho, são:

- Desenvolver uma arquitetura de software e implementar um protocolo de
comunicação capaz de realizar a comunicação entre EMAs e o Servidor.
- Desenvolver uma EMA simulada e um sistema Web de gerenciamento dos das EMAs.
- Construir conhecimento sobre o assunto para motivar o desenvolvimento de trabalhos futuros.

O sistema desenvolvido deve permitir acesso às informações coletadas pelas referidas estações, possibilitando o conhecimento da situação climática de um determinado local, bem como, das informações meteorológicas armazenadas ao longo do tempo, via sistema Web de gerenciamento, permitindo a realização de estudos mais aprofundados sobre o comportamento do clima local. É importante ressaltar que nenhuma estação meteorológica foi desenvolvida, pois o trabalho em questão faz uso de estações meteorológicas automáticas simuladas, implementadas em máquinas virtuais utilizando o sistema operacional Raspbian, do hardware Raspberry Pi.

Com essa breve introdução, se quiser se aprofundar mais no trabalho e ver mais sobre o desenvolvimento dele, sinta-se livre para consultar o meu <a href="https://github.com/AX414/tcc-bcc/blob/main/Artigos/Levantamento%20Bibliogr%C3%A1fico%20Final%20-%20Porcel.pdf">Levantamento Bibliográfico Final</a> 📖.


</details>

<details>
<summary><b>Instalações necessárias</b></summary>

Para executar o portal web, você pode utilizar o XAMPP, pois é um ambiente de desenvolvimento de código aberto e gratuito que permite instalar e configurar rapidamente um servidor web local. Ele é composto por uma distribuição do Apache, MySQL, PHP e Perl, e é considerado o ambiente de desenvolvimento PHP mais popular. Enfim, instale ele, coloque o projeto do portalEMA dentro da pasta ``htdocs`` dele e inicie o servidor.

O broker MQTT utilizado neste trabalho é o Mosquitto. Seu uso se deve ao
fato dele ser leve e adequado para o uso em diversos dispositivos, desde computadores de baixa potência com placa única até em servidores completos. Sua instalação também é simples e ele é muito indicado para utilização com sensores (residenciais e industriais). Além disso, seu código é aberto e está disponível gratuitamente

Para aqueles que ainda não conhecem sobre, o Mosquitto é um broker do protocolo IoT chamado MQTT, com ele podemos utilizar o broker para realizar a publicação de nossas mensagens de um módulo da arquitetura de software proposta pelo trabalho. Para maiores informações sobre como isso irá ocorrer, aconselho fortemente ler meus levantamentos bibliográficos.

Este trabalho também faz uso do Apache Kafka, que é uma plataforma de
transmissão de dados capaz de publicar, assinar, armazenar e processar fluxos de registro em tempo real. O Apache Kafka foi desenvolvido para efetuar o processamento de fluxos de dados provenientes de diversas fontes e entregá-los a uma grande variedade de clientes. A ferramenta é capaz de não só movimentar grandes volumes de um ponto A ao ponto B, mas também de A até Z e para qualquer outro local que for necessário simultaneamente, tornando essa tecnologia excelente para dimensionamento

Também será necessário utilizar o virtual box para emular o sistema operacional Linux, eu pessoalmente vou utilizar o sistema operacional Raspbian, do hardware Raspberry Pi, a versão pode ser a mais atual.

Sabendo disso, aqui está os links de download, não é necessário uma versão específica deles, a mais atual já serve: <a href="https://mosquitto.org/download/">Mosquitto</a> | <a href="https://kafka.apache.org/downloads"> Apache Kafka</a> | <a href="https://www.apachefriends.org/pt_br/download.html">XAMPP</a> | <a href="https://www.virtualbox.org/wiki/Downloads">
Virtual Box</a> | <a href="https://www.raspberrypi.com/software/raspberry-pi-desktop/">Raspberry Pi Desktop</a>


<details>
<summary><b>Como utilizar</b></summary>

Eu utilizei o Windows e o Linux para o desenvolvimento deste trabalho, então é necessário ressaltar a forma correta de instalar, configurar e inicializar essas ferramentas também.

<details>
<summary><b>Windows</b></summary>

##### Mosquitto no Windows:

Após sua instalação, vá até sua pasta e execute cada um desses comandos para testar:

Inicializar o sub: ``mosquito_sub -t topico -h localhost``

Inicializar o pub em outro terminal e enviar a mensagem para teste: ``mosquito_pub -t topico -h localhost -m "temperatura: 30"``

#### Kafka no Windows:

Após efetuar o download do kafka, extraia ele na pasta raiz do computador, abra o prompt de comando do windows, vá até a pasta do kafka e você deverá iniciar o zookeeper: ``bin\windows\zookeeper-server-start.bat config\zookeeper.properties``

Espere o zookeeper inicializar e depois inicie o kafka: ``bin\windows\kafka-server-start.bat config\server.properties``

Com esses comandos o kafka já irá estar funcionando. Eu adicionei na pasta alguns códigos .BAT para agilizar esse processo, então você poderá abrir eles como um arquivo de texto e editar o que for necessário para você rodar tudo de forma mais rápida sem a necessidade de abrir o prompt e digitar tudo isso.

</details>

<details>
<summary><b>(Máquina Virtual) Linux</b></summary>

#### Instalando o mosquitto e bibliotecas do python:

Estes comandos devem ser executados no terminal do Linux, vale ressaltar que a minha máquina possui o ``Python v3.12.2`` e o ``pip v24.2``.

- ``sudo apt-get install mosquitto``
- ``sudo apt-get install mosquitto-clients``
- ``pip install paho-mqtt mysql-connector-python geopy pykafka kafka-python pymongo jsonschema``

#### Configurações do mosquitto.conf:

Após instalar o broker ``Mosquitto``, é necessário configurar ele, geralmente ele ficará localizado na pasta ``etc``, porém, se não encontrá-lo, utilize o comando ``whereis mosquitto``, este comando deve ajudar a encontrar a pasta do broker baixado. Dentro da pasta dele, deve haver um arquivo de configuração chamado ``conf.d``, altere ele para que ele se assemelhe ao conteúdo abaixo.

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

> <b>OBS.:</b> Aconselho ligar e desligar o serviço do mosquito para toda configuração efetuada aqui, inclusive logo após sua instalação com:
``sudo service mosquitto stop`` -> ``sudo service mosquitto start`` -> ``sudo service mosquitto status``.

#### Kafka no Linux:

É necessário estar na pasta do kafka que foi baixado e extraído, após isso, utilize estes comandos:

Entre como super usuário: ``sudo su``

Inicializar o zookeeper: ``bin/zookeeper-server-start.sh config/zookeeper.properties``.

Inicializar o kafka: ``bin/kafka-server-start.sh config/server.properties``.

</details>
</details>
</details>


</html>
