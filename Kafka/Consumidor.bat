@echo off
cd C:\Users\User\kafka_INSIRA A VERSÃO DO KAFKA

rem Visualiza todas as mensagens desde o início que estão no tópico
start cmd /k "bin\windows\kafka-console-consumer.bat --bootstrap-server localhost:9092 --topic NOME DO TÓPICO --from-beginning"
