<html>
<h1>EMA e Protocolo</h1>

![Arquitetura de Software proposta pelo trabalho]()

<details>
  <summary>EMA</summary>

Para a entrega de mensagens, foi desenvolvido uma EMA simulada utilizando uma máquina
virtual Raspbian, o uso deste sistema operacional se deve ao fato de ser um sistema operacional
baseado em Debian para o hardware Raspberry Pi, que é um computador pequeno integrado
em uma placa de circuito, frequentemente utilizado para soluções IoT como no caso de casas
inteligentes, desenvolvimento de robôs, mini servidores, centrais de multimídia, estações
meteorológicas e muitos outros projetos (RASPBERRY PI FOUNDATION, 2023).

Na estação simulada, se encontra o broker MQTT, Mosquitto, ele é utilizado para
publicar as mensagens do módulo de “Coletor de Dados” da estação, este módulo é um software
implementado em python na EMA simulada que gera dados randomizados e os encaminha para
um tópico do broker como um arquivo JSON.

Após o envio do arquivo ser efetuado e ele ser devidamente publicado no tópico do
broker, o módulo de “Gerenciador de Comunicação” que também é um software implementado
em python na EMA simulada, irá consumir esta mensagem e por sua vez produz ela em um
tópico específico da estação no Gerenciador de Fila de Mensagens que está alocado no servidor.
</details>

<details>
  <summary>Protocolo de Comunicação</summary>

Para permitir a comunicação entre EMAs e o servidor foi necessário desenvolver um protocolo
de comunicação que atua na camada de aplicação do modelo ISO/OSI. Ele foi denominado
``MIAP (Meteorological Information Application Protocol)`` ou ``(Protocolo de Aplicação de
Informações Meteorológicas)`` e foi desenvolvido para trabalhar junto ao gerenciador de fila de
mensagens Kafka, definindo como a comunicação entre as EMAs e servidor deve ser realizada.
A tabela ilustra as camadas de rede e as tecnologias utilizadas em cada camada.

| Camadas | Protocolos |
| ------------- | ------------- |
| Camada de Aplicação | MIAP + KAFKA |
| Camada de Transporte | TCP |
| Camada de Rede | IP |
| Camada Física + Enlace | Ethernet / 3G / 4G / etc... |

<details>
  <summary>Mensagens</summary>
</details>

As mensagens MIAP serão enviadas entre EMAs e servidor utilizando o Kafka, que foi
selecionado por possuir características importantes para esta tarefa, como por exemplo, o fato
de ser um sistema de fila de mensagem tolerante a falhas permitindo hospedar diversos agentes
do Kafka em servidores distintos, a escalabilidade que permite adicionar partições para os
tópicos das mensagens para distribuir o carregamento da mensagem de forma uniforme
(AMAZON, 2023).

A escolha do formato JSON para a transmissão dos dados se deve ao fato de sua sintaxe
ser mais compacta, se comparado a outras tecnologias que possuem o mesmo objetivo (ex:
XML), proporcionando uma economia de dados que pode ser vantajosa em termos de
desempenho de transmissão. Os documentos JSON também são mais simples para o ser
humano compreender, se comparados ao XML. Além disso, é considerado um formato flexível,
sendo compatível com diversos tipos de dados (AMAZON, 2023).

O protocolo possui um tipo de mensagem apenas, porém possui dois atributos
importantes. O primeiro atributo (“observacao”) possui os dados meteorológicos coletados
pelas EMAs e enviados ao servidor, como por exemplo, temperatura do ar, direção e velocidade
do vento, umidade relativa do ar e outras informações pertinentes. O segundo atributo
“diagnostico”, possui dados de diagnóstico de uma EMA que possibilita aos operadores do
sistema conhecer o estado atual de cada EMA, como por exemplo, o status, carga da bateria,
uptime, entre outras informações pertinentes.

As mensagens são enviadas das EMAs para o servidor em intervalos de cinco (5)
minutos. O formato da mensagem utilizado pelo protocolo se encontra na página 27 da versão final 
do <a href="https://github.com/AX414/tcc-bcc/blob/main/Artigos/Levantamento%20Bibliogr%C3%A1fico%20Final%20-%20Porcel.pdf">Levantamento Bibliográfico</a>.

Para validar os arquivos recebidos, é utilizado o JSON Schema, a mensagem possui um
Schema correspondente que se encontra <a href="https://github.com/AX414/tcc-bcc/blob/main/schema.json">aqui</a>.

<details>
  <summary>Formalização do Protocolo</summary>

A Figura a seguir apresenta um diagrama de estados da Mensagem do Protocolo, com ele é possível
ver a ilustração de como é efetuado o processo do envio da mensagem pelo protocolo.

![Diagrama de Estados Finitos da Mensagem do Protocolo]()

Após os dados serem coletados pelos sensores da EMA, os dados estão preparados para
o envio, após cinco (5) minutos, o envio é efetuado e os dados são recebidos pelo servidor.

No servidor, os dados recebidos são validados pelo JSON Schema, caso o formato do
arquivo esteja válido de acordo com o Schema, os valores são verificados para determinar se
algum deles esteja fora do padrão, em caso afirmativo, os valores serão persistidos com o erro
informando qual sensor apresentou o erro. Caso nenhum sensor possua erro, os dados serão
persistidos normalmente.

Caso o formato do dos dados esteja inválido de acordo com o JSON Schema, o protocolo
dará início ao tratamento de erros e em seguida irá persistir os dados informando que o formato
está inválido de acordo com o Schema.
</details>


</details>

</html>
