CREATE TABLE DemoSilex (
  id int unsigned NOT NULL AUTO_INCREMENT,
  nombre varchar(50) NOT NULL,
  correo varchar(30) NOT NULL,
  mensaje text NOT NULL,
  fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB, CHARSET=utf8;