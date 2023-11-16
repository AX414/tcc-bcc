@echo off
set /p topico=Digite o nome do tópico que deseja deletar: 

cd C:\kafka_2.13-3.6.0\bin\windows
kafka-topics.bat --delete --topic %topico% --bootstrap-server localhost:9092

echo Tópico %topico% deletado com sucesso.
pause
