PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "unidades" (
	"id_unidade" INTEGER PRIMARY KEY AUTOINCREMENT,
	"nome_unidade" VARCHAR NOT NULL,
	"classe_base" VARCHAR NOT NULL DEFAULT 'Amigo/Companheiro'
);
INSERT INTO unidades VALUES(1,'Unidade Águia','Amigo/Companheiro');
INSERT INTO unidades VALUES(7,'gorlias','base222');
INSERT INTO unidades VALUES(8,'controle 123','pesquisador/pioneiro');
CREATE TABLE IF NOT EXISTS "desbravadores" (
	"id_desbravador" INTEGER PRIMARY KEY AUTOINCREMENT,
	"nome_completo" VARCHAR NOT NULL,
	"id_unidade" INT NOT NULL,
	"cargo" VARCHAR NOT NULL DEFAULT 'Membro'
);
INSERT INTO desbravadores VALUES(1,'João Silva 3',2,'Secretário');
INSERT INTO desbravadores VALUES(2,'joão Luizão silva',2,'Secretário');
INSERT INTO desbravadores VALUES(3,'evandro laercio ribeiro',2,'Secretário');
INSERT INTO desbravadores VALUES(4,'Abraão 2',1,'Desbravador');
INSERT INTO desbravadores VALUES(7,'mirosvaldo andre',1,'Desbravador');
INSERT INTO desbravadores VALUES(8,'teste contrato',1,'Secretário');
CREATE TABLE IF NOT EXISTS "pontuacao_diaria" (
	"id_ponto" INTEGER PRIMARY KEY AUTOINCREMENT,
	"id_desbravador" INTEGER NOT NULL,
	"data_reuniao" DATE NOT NULL,
	"pontualidade" INTEGER NOT NULL DEFAULT 0,
	"uniforme" INTEGER NOT NULL DEFAULT 0,
	"espiritual" INTEGER NOT NULL DEFAULT 0,
	"classe" INTEGER NOT NULL DEFAULT 0,
	"comportamento" INTEGER NOT NULL DEFAULT 0,
	"tesouraria" INTEGER NOT NULL DEFAULT 0,
	"bonus" INTEGER NOT NULL DEFAULT 0,
	"total_dia" INTEGER NOT NULL DEFAULT 0,
	CONSTRAINT fk_desbravador FOREIGN KEY (id_desbravador) REFERENCES desbravadores(id_desbravador) ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO pontuacao_diaria VALUES(1,1,'2025-12-21',10,15,20,20,15,10,0,90);
INSERT INTO pontuacao_diaria VALUES(2,1,'2025-12-31',10,15,20,20,15,10,0,90);
INSERT INTO pontuacao_diaria VALUES(3,3,'2025-12-31',8,7,11,0,0,0,0,26);
INSERT INTO pontuacao_diaria VALUES(4,2,'2025-12-31',10,0,0,0,0,0,0,10);
CREATE TABLE cantinho (
	id integer primary key AUTOINCREMENT,
	id_dbv integer,
	id_unit integer,
	presenca char(1),
	uniforme char(1),
	atividades char(1),
	hino char(1) 
);
INSERT INTO cantinho VALUES(1,1,7,'S','1','','');
INSERT INTO cantinho VALUES(3,3,8,'1','1','','');
INSERT INTO cantinho VALUES(4,3,8,NULL,NULL,NULL,NULL);
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('unidades',9);
INSERT INTO sqlite_sequence VALUES('desbravadores',8);
INSERT INTO sqlite_sequence VALUES('pontuacao_diaria',4);
INSERT INTO sqlite_sequence VALUES('cantinho',4);
COMMIT;
