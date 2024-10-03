<html>
  <h1>Sistema de Gerenciamento Web</h1>

![Arquitetura de Software proposta pelo trabalho](https://github.com/AX414/tcc-bcc/blob/main/Implementa%C3%A7%C3%A3o/Imagens/Arquitetura%20do%20Projeto.png?raw=true)
> Arquitetura de Software proposta pelo trabalho.

O Sistema Web de Gerenciamento se encontra no servidor de acordo com a arquitetura de software proposta, implementado em PHP
que tem a função de permitir que os usuários do sistema tenham acesso aos dados atuais de
cada EMA, bem como, aos dados históricos da mesma, podendo inclusive baixá-los em formato
CSV para utilização em estudos futuros.

O sistema implementado teve como inspiração o portal REDEMET (Rede de
Meteorologia do Comando da Aeronáutica) que é a plataforma oficial utilizada pelo DECEA
(Departamento de Controle do Espaço Aéreo) para fornecer dados meteorológicos que são
importantes para identificar fenômenos que podem influenciar nas atividades de navegação
aérea. É importante destacar que assim como a REDEMET, o sistema de gerenciamento
permite o acesso aos dados meteorológicos por meio de um mapa que facilita a localização de
cada estação disponível e conforme a figura abaixo.

![portalEMA-01](https://github.com/AX414/tcc-bcc/blob/main/Implementa%C3%A7%C3%A3o/Imagens/portalEMA01.png)
> Página inicial do Sistema de Gerenciamento Web.

O Sistema possui dois (2) níveis de acesso, sendo eles o Administrador e o Cliente,
apesar disso também é possível utilizá-lo mesmo não sendo um usuário autenticado.
No nível de acesso do Administrador, é possível manter os dados de novos usuários
informando seus dados pessoais e o nível de acesso do novo usuário, também é possível manter
dados das EMAs informando a localização (latitude e longitude), um nome para a estação e se
deseja que os dados dela sejam públicos para qualquer pessoa consultar, após o cadastro da
EMA, um tópico será gerado no Gerenciador de Fila de Mensagens que se encontra no servidor,
além disso o Administrador pode consultar os dados dos relatórios das EMAs cadastradas no
sistema.

No nível de acesso do Cliente, ele pode manter dados de suas EMAs apenas e consultar
os relatórios recebidos pela estação. Usuários não autenticados no sistema podem usá-lo e ter acesso à EMAs públicas, isso
irá permitir que o usuário possa consultar dados dos relatórios recebidos pela estação. Além
disso, usuários não autenticados também poderão se cadastrar no sistema.
A distinção de se uma EMA é pública, privada ou se ela é sua ocorre de acordo com a
cor dela. Se um usuário não autenticado estiver utilizando o sistema, apenas EMAs públicas
estarão visíveis para ele, se ele estiver autenticado, uma estação pública que não é dele terá a
cor azul, uma estação dele terá a cor verde e no caso do Administrador que pode ver EMAs
privadas de outros usuários, será a cor vermelha, conforme a figura a seguir.

![portalEMA-02](https://github.com/AX414/tcc-bcc/blob/main/Implementa%C3%A7%C3%A3o/Imagens/portalEMA02.png)
> Exemplo de EMA privada.

Todos os usuários (autenticados ou não) ao consultarem os relatórios, podem efetuar o
download do histórico de relatórios da estação conforme apresentado a seguir.

![portalEMA-03](https://github.com/AX414/tcc-bcc/blob/main/Implementa%C3%A7%C3%A3o/Imagens/portalEMA03.png)
> Página Inicial de Histórico de Relatórios de uma EMA.

Também é possível consultar um relatório em específico e efetuar seu download
conforme apresentado na a seguir.

![portalEMA-04](https://github.com/AX414/tcc-bcc/blob/main/Implementa%C3%A7%C3%A3o/Imagens/portalEMA04.png)
> Página de consulta de Relatório.

O Sistema de Gerenciamento também permite que o usuário saiba se algum dos sensores
apresenta algum valor fora do padrão informando os valores que há suspeita de erro conforme
a seguir.

![portalEMA-05](https://github.com/AX414/tcc-bcc/blob/main/Implementa%C3%A7%C3%A3o/Imagens/portalEMA05.png)
> Exemplo de Relatório com erro na Temperatura .


</html>
