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

INSERT INTO usuarios VALUES(1,"JP","J.Porcel","adm","$argon2id$v=19$m=65536,t=4,p=1$anpJLmVTUDBhQ0xKVWIxdQ$x5XpaCmcJojWWcLn8Cy9z8Xxn9MvRlmyIycPSad/ZR8",1,1);
INSERT INTO usuarios VALUES(2,"Dark","Dark","cli","$argon2id$v=19$m=65536,t=4,p=1$UTAyaWhkbjVJQzlFYjlPVA$EvIWZJh+o5Wp4cqOm6l2nYCocAJYq78nFwUp/BHoGzI",2,1);
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
  PRIMARY KEY (idema, usuarios_idusuario),
    FOREIGN KEY (usuarios_idusuario)
    REFERENCES usuarios (idusuario))
ENGINE = InnoDB;

INSERT INTO emas VALUES (1, "Morrigan 1","192.168.0.1",1,"-21.78526685","-52.111628826598704", 1,1,"morrigan1");
INSERT INTO emas VALUES (2, "Morrigan 2","192.168.0.1",0,"-21.78","-52.13", 2,1,"morrigan2");
SELECT * FROM emas;
-- -----------------------------------------------------
-- Table relatorio
-- -----------------------------------------------------
DROP TABLE IF EXISTS relatorios ;

CREATE TABLE IF NOT EXISTS relatorios (
  idrelatorio INT NOT NULL AUTO_INCREMENT,
  data DATE NOT NULL,
  hora TIME NOT NULL,
  -- Dados Obrigatórios
  temperatura FLOAT NOT NULL,
  unidade_tem VARCHAR(5) NOT NULL,
  erro_tem BOOLEAN NOT NULL,
  umidade FLOAT NOT NULL,
  unidade_um VARCHAR(5) NOT NULL,
  erro_um BOOLEAN NOT NULL,
  vento_velocidade FLOAT NOT NULL,
  unidade_vv VARCHAR(5) NOT NULL,
  erro_vv BOOLEAN NOT NULL,
  vento_direcao FLOAT NOT NULL,
  unidade_vd VARCHAR(50) NOT NULL,
  erro_vd BOOLEAN NOT NULL,
  -- Dados Opcionais
  radiacao_solar FLOAT NULL DEFAULT NULL,
  unidade_rs VARCHAR(5) NULL DEFAULT NULL,
  erro_rs BOOLEAN NULL,
  pressao_atmos FLOAT NULL DEFAULT NULL,
  unidade_pa VARCHAR(5) NULL DEFAULT NULL,
  erro_pa BOOLEAN NULL,
  volume_chuva FLOAT NULL DEFAULT NULL,
  unidade_vc VARCHAR(5) NULL DEFAULT NULL,
  erro_vc BOOLEAN NULL,
  frequencia_chuva FLOAT NULL DEFAULT NULL,
  unidade_fc VARCHAR(5) NULL DEFAULT NULL,
  erro_fc BOOLEAN NULL,
  -- Dados não previstos serão 
  -- mantidos e salvos como JSON
  nao_previstos JSON NULL DEFAULT NULL,
  erros TEXT NULL DEFAULT NULL,
  emas_idema INT NOT NULL,
  PRIMARY KEY (idrelatorio, emas_idema),
    FOREIGN KEY (emas_idema)
    REFERENCES awsmqtt.emas (idema))
ENGINE = InnoDB;

INSERT INTO relatorios VALUES(1,CURDATE(),CURTIME(),23.5,'°C',false,50.0,'%',false,10.8,'m/s',false,270.0,'graus',false,null,'',false,null,'',false,null,'',false,null,'',false,null,null,1);
INSERT INTO relatorios VALUES(2,CURDATE(),CURTIME(),26,'°C',false,45.0,'%',false,12,'m/s',false,240.0,'graus',false,null,'',false,null,'',false,null,'',false,null,'',false,null,null,1);
SELECT * FROM relatorios;