@echo off
cd C:\Users\User\kafka_INSIRA A VERSÃO DO KAFKA

rem Visualiza os tópicos
start cmd /k "bin\windows\kafka-consumer-groups.bat --all-groups --bootstrap-server localhost:9092 --describe"
