@echo off
cd C:\Users\User\kafka_INSIRA A VERSÃO DO KAFKA

start cmd /k "bin\windows\kafka-topics.bat --bootstrap-server localhost:9092 --describe"
