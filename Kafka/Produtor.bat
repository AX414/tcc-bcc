@echo off
cd C:\Users\User\kafka_INSIRA A VERSÃO DO KAFKA

rem Envia mensagens para o tópico
start cmd /k "bin\windows\kafka-console-producer.bat --broker-list localhost:9092 --topic NOME DO TÓPICO"
