@echo off
cd C:\kafka_2.13-3.6.0\bin\windows
kafka-topics.bat --list --bootstrap-server localhost:9092
pause