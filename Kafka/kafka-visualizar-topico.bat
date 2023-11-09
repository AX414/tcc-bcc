@echo on
cd C:\kafka_2.12-3.5.1\bin\windows
kafka-console-consumer.bat --bootstrap-server localhost:9092 --topic epitacio1 --from-beginning