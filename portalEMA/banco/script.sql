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
  PRIMARY KEY (idusuario))
ENGINE = InnoDB;

INSERT INTO usuarios VALUES(1,"JP","J.Porcel","joao@hotmail.com","$argon2id$v=19$m=65536,t=4,p=1$VE5reS96WFliNmZNSEw2eA$0nz5adtjMYNOsLnEmko/XGclUZWqZ+TNDx8HeUPV6n8",1);
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
  PRIMARY KEY (idema, usuarios_idusuario),
    FOREIGN KEY (usuarios_idusuario)
    REFERENCES usuarios (idusuario))
ENGINE = InnoDB;

INSERT INTO emas VALUES (1, "Morrigan 1","192.168.0.1",1,"-21.78526685","-52.111628826598704", 1);
SELECT * FROM emas;
-- -----------------------------------------------------
-- Table relatorio
-- -----------------------------------------------------
DROP TABLE IF EXISTS relatorios ;

CREATE TABLE IF NOT EXISTS relatorios (
  idrelatorio INT NOT NULL AUTO_INCREMENT,
  data DATE NOT NULL,
  hora TIME NOT NULL,
  temperatura INT NOT NULL,
  pluviometro FLOAT NOT NULL,
  vel_vento FLOAT NOT NULL,
  dir_vento INT NOT NULL,
  emas_idema INT NOT NULL,
  emas_usuarios_idusuario INT NOT NULL,
  PRIMARY KEY (idrelatorio, emas_idema, emas_usuarios_idusuario),
    FOREIGN KEY (emas_idema , emas_usuarios_idusuario)
    REFERENCES emas (idema , usuarios_idusuario))
ENGINE = InnoDB;

SELECT * FROM relatorios;
