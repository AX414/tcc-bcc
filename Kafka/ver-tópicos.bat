@echo off

cd C:\kafka_2.13-3.6.0\bin\windows

rem Armazena a lista de tópicos em um arquivo temporário
kafka-topics.bat --list --bootstrap-server localhost:9092 > tmp_topics_list.txt

rem Verifica se o arquivo está vazio
for %%I in (tmp_topics_list.txt) do (
    if %%~zI equ 0 (
        echo Não há tópicos.
    ) else (
        type tmp_topics_list.txt
    )
)

rem Remove o arquivo temporário
del tmp_topics_list.txt
