DROP SCHEMA IF EXISTS emamqtt;
CREATE SCHEMA IF NOT EXISTS emamqtt DEFAULT CHARACTER SET utf8 ;
USE emamqtt;

-- -----------------------------------------------------
-- Table usuarios
-- -----------------------------------------------------
DROP TABLE IF EXISTS usuarios ;

CREATE TABLE IF NOT EXISTS usuarios (
  idusuario INT NOT NULL AUTO_INCREMENT,
  nome_usuario VARCHAR(45) NOT NULL,
  nome_login VARCHAR(45) NOT NULL,
  senha VARCHAR(45) NOT NULL,
  nivel_acesso INT NOT NULL,
  PRIMARY KEY (idusuario))
ENGINE = InnoDB;

INSERT INTO usuarios VALUES(1,"JP","J.Porcel","123porcel",1);

-- -----------------------------------------------------
-- Table emas
-- -----------------------------------------------------
DROP TABLE IF EXISTS emas ;

CREATE TABLE IF NOT EXISTS emas (
  idema INT NOT NULL AUTO_INCREMENT,
  latitude VARCHAR(45) NULL,
  longitude VARCHAR(45) NULL,
  usuarios_idusuario INT NOT NULL,
  PRIMARY KEY (idema, usuarios_idusuario),
    FOREIGN KEY (usuarios_idusuario)
    REFERENCES usuarios (idusuario))
ENGINE = InnoDB;

INSERT INTO emas VALUES (1, "","", 1);

-- -----------------------------------------------------
-- Table relatorio
-- -----------------------------------------------------
DROP TABLE IF EXISTS relatorio ;

CREATE TABLE IF NOT EXISTS relatorio (
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
