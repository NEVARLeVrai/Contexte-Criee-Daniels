DROP DATABASE IF EXISTS LaCriee;
CREATE DATABASE IF NOT EXISTS LaCriee CHARACTER SET utf8;
USE LaCriee;

CREATE TABLE ACHETEUR(
   idAcheteur INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
   login VARCHAR(7),
   pwd VARCHAR(7),
   raisonSocialeEntreprise VARCHAR(50),
   locRue VARCHAR(50),
   rue VARCHAR(50),
   ville VARCHAR(50),
   codePostal VARCHAR(50),
   numHabilitation VARCHAR(10)
);

CREATE TABLE QUALITE(
   idQualite VARCHAR(1) PRIMARY KEY NOT NULL,
   libelle VARCHAR(50)
);

CREATE TABLE PRESENTATION(
   idPresentation VARCHAR(4) PRIMARY KEY NOT NULL,
   libelle VARCHAR(50)
);

CREATE TABLE BAC(
   idBac VARCHAR(1) PRIMARY KEY NOT NULL,
   tare DECIMAL(15,2)
);

CREATE TABLE ESPECE(
   idEspece VARCHAR(50) PRIMARY KEY NOT NULL,
   nomEspece VARCHAR(50),
   nomCommun VARCHAR(50),
   nomScientifique VARCHAR(200)
);

CREATE TABLE TAILLE(
   idTaille VARCHAR(2) PRIMARY KEY NOT NULL,
   specification VARCHAR(50)
);

CREATE TABLE BATEAU(
   idBateau VARCHAR(50) PRIMARY KEY NOT NULL,
   immatriculation VARCHAR(9)
);

CREATE TABLE FACTURE(
   idFacture INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
   idAcheteur INT NOT NULL
);

CREATE TABLE PECHE(
   idBateau VARCHAR(50) NOT NULL,
   datePeche DATE NOT NULL,
   PRIMARY KEY(idBateau, datePeche)
);

CREATE TABLE LOT(
   idLot INT NOT NULL,
   idBateau VARCHAR(50) NOT NULL,
   datePeche DATE NOT NULL,
   idEspece VARCHAR(50) NOT NULL,
   idTaille VARCHAR(2) NOT NULL,
   idPresentation VARCHAR(4) NOT NULL,
   idBac VARCHAR(50) NOT NULL,
   idAcheteur INT NOT NULL,
   idQualite VARCHAR(50) NOT NULL,
   poidsBrutLot DECIMAL(15,2),
   poidsNet DECIMAL(15,2),
   prixPlancher DECIMAL(15,2),
   prixDepart DECIMAL(15,2),
   prixEncheresMax DECIMAL(15,2),
   dateEnchere DATE,
   heureDebutEnchere TIME,
   codeEtat VARCHAR(50),
   idFacture INT NOT NULL,
   PRIMARY KEY(idLot, idBateau, datePeche)
);

CREATE TABLE POSTER(
   idLot INT NOT NULL,
   idBateau VARCHAR(50) NOT NULL,
   datePeche DATE NOT NULL,
   idAcheteur INT NOT NULL,
   prixEnchere DECIMAL(15,2),
   heureEnchere TIME,
   PRIMARY KEY(idLot, idBateau, datePeche, idAcheteur)
);

CREATE TABLE COMPTE(
   idCompte VARCHAR(30) PRIMARY KEY NOT NULL,
   mdpCompte VARCHAR(255)
);

ALTER TABLE FACTURE
ADD CONSTRAINT fk_acheteur_facture_idAcheteur
FOREIGN KEY (idAcheteur) 
REFERENCES ACHETEUR(idAcheteur);

ALTER TABLE PECHE
ADD CONSTRAINT fk_bateau_peche_idBateau
FOREIGN KEY (idBateau) 
REFERENCES BATEAU(idBateau);

ALTER TABLE LOT
ADD CONSTRAINT fk_peche_lot_idBateau_datePeche
FOREIGN KEY (idBateau, datePeche) 
REFERENCES PECHE(idBateau, datePeche);

ALTER TABLE LOT
ADD CONSTRAINT fk_espece_lot_idEspece
FOREIGN KEY (idEspece) 
REFERENCES ESPECE(idEspece);

ALTER TABLE LOT
ADD CONSTRAINT fk_taille_lot_idTaille
FOREIGN KEY (idTaille) 
REFERENCES TAILLE(idTaille);

ALTER TABLE LOT
ADD CONSTRAINT fk_presentation_lot_idPresentation
FOREIGN KEY (idPresentation) 
REFERENCES PRESENTATION(idPresentation);

ALTER TABLE LOT
ADD CONSTRAINT fk_bac_lot_idBac
FOREIGN KEY (idBac) 
REFERENCES BAC(idBac);

ALTER TABLE LOT
ADD CONSTRAINT fk_acheteur_lot_idAcheteur
FOREIGN KEY (idAcheteur) 
REFERENCES ACHETEUR(idAcheteur);

ALTER TABLE LOT
ADD CONSTRAINT fk_qualite_lot_idQualite
FOREIGN KEY (idQualite) 
REFERENCES QUALITE(idQualite);

ALTER TABLE LOT
ADD CONSTRAINT fk_facture_lot_idFacture
FOREIGN KEY (idFacture) 
REFERENCES FACTURE(idFacture);

ALTER TABLE POSTER
ADD CONSTRAINT fk_lot_poster_idLot_datePeche_idBateau
FOREIGN KEY (idLot, idBateau, datePeche) 
REFERENCES LOT(idLot, idBateau, datePeche);

ALTER TABLE POSTER
ADD CONSTRAINT fk_acheteur_poster_idAcheteur
FOREIGN KEY (idAcheteur) 
REFERENCES ACHETEUR(idAcheteur);

INSERT INTO ACHETEUR
(idAcheteur, login, pwd, raisonSocialeEntreprise, locRue, rue, ville, codePostal, numHabilitation)
VALUES
(1, "pfister", "sioslam", "TLAD", "4", "rue Schoch", "STRASBOURG", "67000", "CP12345678");

INSERT INTO QUALITE
(idQualite, libelle)
VALUES 
("E", "extra"),
("A", "glacé"),
("B", "déclassé");

INSERT INTO PRESENTATION
(idPresentation, libelle)
VALUES 
("ENT", "entier"),
("VID", "vidé");

INSERT INTO BAC
(idBac, tare)
VALUES
("B", 2.50),
("F", 4.00);

INSERT INTO ESPECE
(idEspece, nomEspece, nomCommun, nomScientifique)
VALUES
("LJL", "LJAUL", "lieu jaune ligne", "polliachus polliachus");

INSERT INTO TAILLE
(idTaille, specification)
VALUES
("10", "taille 1"),
("20", "taille 2"),
("30", "taille 3"),
("40", "taille 4"),
("50", "taille 5");

INSERT INTO BATEAU
(idBateau, immatriculation)
VALUES
("KORRI", "AA 123456");

INSERT INTO FACTURE
(idFacture, idAcheteur)
VALUES
(1, 1);

INSERT INTO PECHE
(idBateau, datePeche)
VALUES
("KORRI", "2024-11-27");

INSERT INTO LOT
(idLot, idBateau, datePeche, idEspece, idTaille, idPresentation, idBac, idAcheteur, idQualite, poidsBrutLot, poidsNet, prixPlancher, prixDepart, prixEncheresMax, dateEnchere, heureDebutEnchere, codeEtat, idFacture)
VALUES
(11000, "KORRI", "2024-11-27", "LJL", "20", "VID", "B", 1, "E", 15.00, 12.50, 5.00, 10.00, 20.00, "2024-11-27", "14:00:00", "codeEtat", 1);

INSERT INTO POSTER
(idLot, idBateau, datePeche, idAcheteur, prixEnchere, heureEnchere)
VALUES
(11000, "KORRI", "2024-11-27", 1, 10.00, "14:30:00");