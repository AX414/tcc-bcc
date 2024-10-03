# <b>Modelagem do Banco de Dados</b>

A modelagem do banco de dados utilizada pelo sistema de gerenciamento é ilustrada pela figura a seguir, nela é possível ver que o banco de dados possui três tabelas, sendo elas: ``usuarios``, ``emas`` e ``observacoes``.

![Modelagem do Banco de Dados](https://github.com/AX414/tcc-bcc/blob/main/Implementa%C3%A7%C3%A3o/Modelagem%20do%20Banco/modelagem.png)

A tabela de ``usuarios`` é utilizada para armazenar as informações dos usuários que utilizam o sistema de gerenciamento, ela possui uma relação de um para muitos com a tabela ``emas``, devido a um usuário estar vinculado à diversas estações que ele venha a cadastrar.

A tabela ``emas`` é responsável por guardar as informações das estações que estão sendo cadastradas. Ela, além de se relacionar com a tabela de ``usuarios`` se relaciona com a tabela de ``observacoes``, nesse sentido, uma EMA possui diversas observações meteorológicas.

A tabela de ``observacoes`` é responsável por guardar toda a informação que for captada pela ema, além das observações meteorológicas, a tabela também consta com os dados de diagnóstico da estação, contando com atributos como a carga da bateria, uptime e afins

Para retornar à página inicial clique <a href="https://github.com/AX414/tcc-bcc/">aqui</a>.

