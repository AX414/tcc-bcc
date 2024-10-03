@echo off
cd C:\Users\User\kafka_INSIRA A VERSÃO DO KAFKA

rem Visualiza os tópicos
start cmd /k "bin\windows\kafka-topics.bat --list --bootstrap-server localhost:9092"
