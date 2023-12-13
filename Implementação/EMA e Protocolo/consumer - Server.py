import random
import mysql.connector
import json
import jsonschema
import datetime
from jsonschema import validate
from mysql.connector import Error
from datetime import date, time
from paho.mqtt import client as mqtt_client
from kafka import KafkaConsumer
from kafka.admin import KafkaAdminClient, NewTopic


# Client Admin
admin_client = KafkaAdminClient(bootstrap_servers=['192.168.1.6:9092'])
#admin_client = KafkaAdminClient(bootstrap_servers=['10.117.73.251:9092'])
#admin_client = KafkaAdminClient(bootstrap_servers=['localhost:9092'])

# Configurando o consumer
consumer = KafkaConsumer(bootstrap_servers='192.168.1.6:9092')
#consumer = KafkaConsumer(bootstrap_servers='10.117.73.251:9092')
#consumer = KafkaConsumer(bootstrap_servers='localhost:9092')


# Configurando o DB
connection = mysql.connector.connect(host='localhost', database='awsmqtt', user='root', password='ifsp')
cursor = connection.cursor()

# JSON Schema
with open('./jsons/schema.json', encoding="utf8") as schema:
    json_schema = json.load(schema)

# Comandos para deletar e criar tópicos, se necessário
def delete_topic(topico):
    topicos_existentes = consumer.topics()
    print(list(topicos_existentes))
    try:
        if topico in topicos_existentes:
            admin_client.delete_topics(topics=topico)
            print("Tópico deletado.")
        else:
            print("Este tópico não existe.")
    except Exception as error:
        print(error)

def create_topic(topico):
    topicos_existentes = consumer.topics()
    lista_topicos = []
    if topico not in topicos_existentes:
        #print(f'Topico: {topico} adicionado na lista.')
        lista_topicos.append(NewTopic(name=topico, num_partitions=1, replication_factor=1))
    #else:
        #print(f'O tópico {topico}, já existe na lista')
    try:
        if lista_topicos:
            admin_client.create_topics(new_topics = lista_topicos, validate_only=False,)
            print("Tópico criado com sucesso")
        #else:
            #print("Tópico já existe")
    except Exception as error:
        print(error)

# Pega todos os tópicos e se inscreve neles
def atualiza_topicos(consumer):
    query = 'SELECT topico_kafka FROM emas'
    cursor.execute(query)
    result = cursor.fetchall()
    topicos = [row[0] for row in result]

    for topico in topicos:
        if topico not in consumer.topics():
            create_topic(topico)

    consumer.subscribe(topicos)

# Conecta no banco de dados
def connect_database():
    try:
        if connection.is_connected():
            cursor = connection.cursor()
            cursor.execute("select database();")
            record = cursor.fetchone()
            print(f"Conectado ao banco de dados")
    except Exception as e:
        print(f"Erro: {e}")

# Desconecta do banco de dados
def disconnect_database():
    cursor = connection.cursor()
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("Conexão encerrada")

# Pega a mensagem por parâmetro e compara com o JSON Schema para ver se é válida
def validar_mensagem(mensagem):
    try:
        validate(instance=mensagem, schema=json_schema)
        print("\nA mensagem é válida de acordo com o JSON Schema.")
        return True
    except jsonschema.exceptions.ValidationError as e:
        print(f"A mensagem não é válida de acordo com o JSON Schema: {e}")
        return False

# Encontra o id da EMA de acordo com o tópico do kafka que ela têm no banco de dados
def encontrar_idema(topico_kafka):
    try:
        cursor = connection.cursor()
        query = f"SELECT idema FROM emas WHERE topico_kafka = '{topico_kafka}'"
        cursor.execute(query)
        result = cursor.fetchone()
        if result:
            idema = result[0]
            return idema
        else:
            print(f"EMA de tópico '{topico_kafka}' não encontrado.")
            return None
    except Exception as e:
        print(f"Erro ao encontrar EMA: {e}")
        return None

# Persiste as mensagens no banco de dados
def persistir_msg(aux):
    observacao = json.loads(aux)
    erros = ""
    
    idema = encontrar_idema(observacao['observacao']['topico'])

    if(validar_mensagem(observacao)):
        data_leitura = observacao['observacao']['data_leitura']
        hora_leitura = observacao['observacao']['hora_leitura']
        
        # Campos Obrigatórios
        temperatura = observacao['observacao']['obrigatorio']['temperatura']['valor']
        unidade_tem = observacao['observacao']['obrigatorio']['temperatura']['unidade']
        erro_tem = False
        if(temperatura<-20 or temperatura>45):
            erro_tem = True
            erros += "\nErro no sensor de Temperatura."

        umidade = observacao['observacao']['obrigatorio']['umidade']['valor']
        unidade_um = observacao['observacao']['obrigatorio']['umidade']['unidade']
        erro_um = False
        if(umidade<0 or umidade>100):
            erro_um = True
            erros+= "\nErro no sensor de Umidade."

        velocidade_vento = observacao['observacao']['obrigatorio']['velocidade_vento']['valor']
        unidade_vv = observacao['observacao']['obrigatorio']['velocidade_vento']['unidade']
        erro_vv = False
        if(velocidade_vento<0 or velocidade_vento>50):
            erro_vv = True
            erros+= "\nErro no sensor de Velocidade do Vento."

        direcao_vento = observacao['observacao']['obrigatorio']['direcao_vento']['valor']
        unidade_dv = observacao['observacao']['obrigatorio']['direcao_vento']['unidade']
        erro_dv = False
        if(direcao_vento<0 or direcao_vento>360):
            erro_dv = True
            erros+= "\nErro no sensor de Direção do Vento."

        # Campos Opcionais
        radiacao_solar = observacao['observacao']['opcional']['radiacao_solar']['valor']
        unidade_rs = observacao['observacao']['opcional']['radiacao_solar']['unidade']
        erro_rs = False
        if(radiacao_solar<0 or radiacao_solar>1500):
            erro_rs = True
            erros+= "\nErro no sensor de Radiação Solar."
        
        pressao_atmos = observacao['observacao']['opcional']['pressao_atmos']['valor']
        unidade_pa = observacao['observacao']['opcional']['pressao_atmos']['unidade']
        erro_pa = False
        if(pressao_atmos<800 and pressao_atmos>1100):
            erro_pa = True
            erros+= "\nErro no sensor de Pressão Atmosférica."
        
        volume_chuva = observacao['observacao']['opcional']['volume_chuva']['valor']
        unidade_vc = observacao['observacao']['opcional']['radiacao_solar']['unidade']
        erro_vc = False
        if(volume_chuva<0 and volume_chuva>100):
            erro_vc = True
            erros+= "\nErro no sensor de Volume da Chuva."
        
        frequencia_chuva = observacao['observacao']['opcional']['frequencia_chuva']['valor']
        unidade_fc = observacao['observacao']['opcional']['frequencia_chuva']['unidade']
        erro_fc = False
        if(frequencia_chuva<0 and frequencia_chuva>100):
            erro_fc = True
            erros+= "\nErro no sensor de Frequência da Chuva."

        # Dados não previstos
        observacoes_nao_previstas = json.dumps(observacao['observacao']['observacoes_nao_previstas'])

        # Dados de diagnóstico
        status_ema = observacao['diagnostico']['status_ema']
        carga_bateria = observacao['diagnostico']['carga_bateria']
        uptime = observacao['diagnostico']['uptime']
        diagnosticos_nao_previstos = json.dumps(observacao['diagnostico']['diagnosticos_nao_previstos'])

        print(f"Mensagem consumida pelo tópico: {observacao['observacao']['topico']}.")
        print("================================================================================================")
        print(f"Mensagem recebida: {observacao}")
        print("================================================================================================\n")

        if idema is not None:
            # Envia os dados da Observação Meteorológica. 
            query = 'INSERT INTO observacoes(data, hora, temperatura, unidade_tem, erro_tem,'
            query += 'umidade, unidade_um, erro_um, vento_velocidade, unidade_vv, erro_vv,'
            query += 'vento_direcao, unidade_vd, erro_vd, radiacao_solar, unidade_rs, erro_rs,'
            query += 'pressao_atmos, unidade_pa, erro_pa, volume_chuva, unidade_vc, erro_vc,'
            query += 'frequencia_chuva, unidade_fc, erro_fc, observacoes_nao_previstas, erros,'
            query += 'emas_idema) '
            query += f'VALUES("{data_leitura}", "{hora_leitura}", {temperatura}, "{unidade_tem}", {erro_tem}, {umidade}, "{unidade_um}", {erro_um},{velocidade_vento}, "{unidade_vv}", {erro_vv}, {direcao_vento}, "{unidade_dv}", {erro_dv}, {radiacao_solar}, "{unidade_rs}", {erro_rs}, {pressao_atmos}, "{unidade_pa}", {erro_pa}, {volume_chuva}, "{unidade_vc}", {erro_vc},{frequencia_chuva}, "{unidade_fc}", {erro_fc}, \'{observacoes_nao_previstas}\',"{erros}", {idema})'
            cursor.execute(query)
            connection.commit()

            # Envia os dados do diagnóstico para a EMA.
            query = 'UPDATE emas SET status_ema = %s, carga_bateria = %s, uptime = %s, diagnosticos_nao_previstos = %s WHERE idema = %s'
            cursor.execute(query, (status_ema, carga_bateria, uptime, diagnosticos_nao_previstos, idema))
            connection.commit()
        else:
            print("Não foi possível encontrar a EMA correspondente.")
    else:
        print("\nMensagem inválida de acordo com o JSON Schema. Persistindo com valores de erro.")
        erros+="\nErro no JSON, ele não estava de acordo com o JSON Schema utilizado."
        erros+=f"\nObservação errada:\n\n{observacao}"
        data_leitura = datetime.date.today()
        hora_atual = datetime.datetime.now().time()
        hora_leitura = hora_atual.strftime("%H:%M:%S")
        print(hora_leitura)
        if idema is not None:
            query = 'INSERT INTO observacoes (data, hora, erros, emas_idema) '
            query += f'VALUES("{data_leitura}", "{hora_leitura}","{erros}", {idema})'
            cursor.execute(query)
            connection.commit()
        else:
            print("Não foi possível encontrar a EMA correspondente.")

def run():
    connect_database()
    atualiza_topicos(consumer)
    print(consumer.topics())
    for msg in consumer:
        atualiza_topicos(consumer)
        #print(f"{msg.topic} -> {msg.value.decode('utf-8')}")
        aux = msg.value.decode('utf-8')
        persistir_msg(aux)
    
    consumer.close()

if __name__ == '__main__':
    run()