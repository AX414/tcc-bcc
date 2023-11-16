DROP SCHEMA IF EXISTS awsmqtt;
CREATE SCHEMA IF NOT EXISTS awsmqtt DEFAULT CHARACTER SET utf8 ;
USE awsmqtt;

-- -----------------------------------------------------
-- Table usuarios
-- -----------------------------------------------------
DROP TABLE IF EXISTS usuarios ;

CREATE TABLE IF NOT EXISTS usuarios (
  idusuario INT NOT NULL AUTO_INCREMENT,
  nome_usuario VARCHAR(45) NOT NULL,
  nome_login VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  senha VARCHAR(100) NOT NULL,
  nivel_acesso INT NOT NULL,
  ativo INT NOT NULL,
  PRIMARY KEY (idusuario))
ENGINE = InnoDB;

INSERT INTO usuarios VALUES(1,"Administrador","adm","adm","$argon2id$v=19$m=65536,t=4,p=1$anpJLmVTUDBhQ0xKVWIxdQ$x5XpaCmcJojWWcLn8Cy9z8Xxn9MvRlmyIycPSad/ZR8",1,1);
INSERT INTO usuarios VALUES(2,"Cliente","cli","cli","$argon2id$v=19$m=65536,t=4,p=1$UTAyaWhkbjVJQzlFYjlPVA$EvIWZJh+o5Wp4cqOm6l2nYCocAJYq78nFwUp/BHoGzI",2,1);
SELECT * FROM usuarios;
-- -----------------------------------------------------
-- Table emas
-- -----------------------------------------------------
DROP TABLE IF EXISTS emas ;

CREATE TABLE IF NOT EXISTS emas (
  idema INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL,
  ip VARCHAR(45) NOT NULL,
  publica INT NOT NULL,
  latitude VARCHAR(45) NOT NULL,
  longitude VARCHAR(45) NOT NULL,
  usuarios_idusuario INT NOT NULL,
  ativa INT NOT NULL,
  topico_kafka VARCHAR (45) NOT NULL,
  -- Dados referente ao diagnostico
  status_ema VARCHAR (10) NULL,
  carga_bateria FLOAT NULL, 
  uptime VARCHAR(50) NULL,
  diagnosticos_nao_previstos JSON NULL,
  PRIMARY KEY (idema, usuarios_idusuario),
    FOREIGN KEY (usuarios_idusuario)
    REFERENCES usuarios (idusuario))
ENGINE = InnoDB;

-- IF de Epitácio
INSERT INTO emas VALUES (1, "Epitácio","192.168.0.1",1,"-21.78526685","-52.111628826598704", 1,1,"epitacio1", "Online", 78.6, "3 dia(s), 4 hora(s), 55 min", null);
-- Bataguassu
INSERT INTO emas VALUES (2, "Bataguassu","192.168.0.1",0,"-21.7155","-52.4196", 1,1,"bataguassu2", "Online", 88.3, "1 dia(s), 8 hora(s), 15 min", null);
-- Venceslau
INSERT INTO emas VALUES (3, "Venceslau","192.168.0.1",1,"-21.8754","-51.8447", 2,1,"venceslau3", "Online", 95.0, "0 dia(s), 18 hora(s), 45 min", null);
-- Prudente
INSERT INTO emas VALUES (4, "Prudente","192.168.0.1",0,"-22.1207","-51.3852", 2,1,"prudente4", "Online", 88.2, "1 dia(s), 8 hora(s), 20 min", null);

SELECT * FROM emas;
-- -----------------------------------------------------
-- Table relatorio
-- -----------------------------------------------------
DROP TABLE IF EXISTS observacoes;

CREATE TABLE IF NOT EXISTS observacoes (
  idobservacao INT NOT NULL AUTO_INCREMENT,
  data DATE NULL,
  hora TIME NULL,
  -- Dados Obrigatórios
  temperatura FLOAT NULL,
  unidade_tem VARCHAR(5) NULL,
  erro_tem BOOLEAN NULL,
  umidade FLOAT NULL,
  unidade_um VARCHAR(5) NULL,
  erro_um BOOLEAN NULL,
  vento_velocidade FLOAT NULL,
  unidade_vv VARCHAR(5) NULL,
  erro_vv BOOLEAN NULL,
  vento_direcao FLOAT NULL,
  unidade_vd VARCHAR(50) NULL,
  erro_vd BOOLEAN NULL,
  -- Dados Opcionais
  radiacao_solar FLOAT NULL,
  unidade_rs VARCHAR(5) NULL,
  erro_rs BOOLEAN NULL,
  pressao_atmos FLOAT NULL,
  unidade_pa VARCHAR(5) NULL,
  erro_pa BOOLEAN NULL,
  volume_chuva FLOAT NULL,
  unidade_vc VARCHAR(5) NULL,
  erro_vc BOOLEAN NULL,
  frequencia_chuva FLOAT NULL,
  unidade_fc VARCHAR(5) NULL,
  erro_fc BOOLEAN NULL,
  -- Dados não previstos serão 
  -- mantidos e salvos como JSON
  observacoes_nao_previstas JSON NULL,
  erros TEXT NULL,
  emas_idema INT NOT NULL,
  PRIMARY KEY (idobservacao, emas_idema),
    FOREIGN KEY (emas_idema)
    REFERENCES awsmqtt.emas (idema))
ENGINE = InnoDB;

INSERT INTO observacoes VALUES(1,CURDATE(),CURTIME(),23.5,'°C',false,50.0,'%',false,10.8,'m/s',false,270.0,'graus',false,null,'',false,null,'',false,null,'',false,null,'',false,null,null,1);
INSERT INTO observacoes VALUES(2,CURDATE(),CURTIME(),26,'°C',false,45.0,'%',false,12,'m/s',false,240.0,'graus',false,null,'',false,null,'',false,null,'',false,null,'',false,null,null,1);
SELECT * FROM observacoes;