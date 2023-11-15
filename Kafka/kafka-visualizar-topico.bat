@echo on
cd C:\kafka_2.13-3.6.0\bin\windows
kafka-console-consumer.bat --bootstrap-server localhost:9092 --topic epitacio1 --from-beginning