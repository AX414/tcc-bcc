@echo off
cd C:\Users\User\kafka_INSIRA A VERSÃO DO KAFKA

rem Conecta com o kafka 9092 e cria o tópico
start cmd /k "bin\windows\kafka-topics.bat --create --bootstrap-server localhost:9092 --replication-factor 1 --partitions 1 --topic NOME DO TÓPICO"
