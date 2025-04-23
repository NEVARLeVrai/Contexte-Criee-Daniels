DROP DATABASE IF EXISTS LaCriee;
CREATE DATABASE IF NOT EXISTS LaCriee CHARACTER SET utf8;
USE LaCriee;

CREATE TABLE COMPTE(
   idCompte VARCHAR(30) PRIMARY KEY NOT NULL,
   mdpCompte VARCHAR(255),
   typeCompte VARCHAR(50)
);

CREATE TABLE ACHETEUR(
   idCompte VARCHAR(30) PRIMARY KEY NOT NULL,
   raisonSocialeEntreprise VARCHAR(50),
   locRue VARCHAR(50),
   rue VARCHAR(50),
   ville VARCHAR(50),
   codePostal VARCHAR(50),
   numHabilitation VARCHAR(50)
);

CREATE TABLE VENDEUR(
   idCompte VARCHAR(30) PRIMARY KEY NOT NULL,
   raisonSocialeEntreprise VARCHAR(50),
   nom VARCHAR(50),
   prenom VARCHAR(50),
   locRue VARCHAR(50),
   rue VARCHAR(50),
   ville VARCHAR(50),
   codePostal VARCHAR(50),
   numHabilitation VARCHAR(50)
);

CREATE TABLE ADMIN(
   idCompte VARCHAR(30) PRIMARY KEY NOT NULL
);

CREATE TABLE QUALITE(
   idQualite VARCHAR(50) PRIMARY KEY NOT NULL,
   libelle VARCHAR(50)
);

CREATE TABLE PRESENTATION(
   idPresentation VARCHAR(50) PRIMARY KEY NOT NULL,
   libelle VARCHAR(50)
);

CREATE TABLE BAC(
   idBac VARCHAR(50) PRIMARY KEY NOT NULL,
   tare DECIMAL(15,2)
);

CREATE TABLE ESPECE(
   idEspece VARCHAR(50) PRIMARY KEY NOT NULL,
   nomEspece VARCHAR(50),
   nomCommun VARCHAR(50),
   nomScientifique VARCHAR(200)
);

CREATE TABLE TAILLE(
   idTaille INT PRIMARY KEY NOT NULL,
   specification VARCHAR(50)
);

CREATE TABLE BATEAU(
   idBateau VARCHAR(50) PRIMARY KEY NOT NULL,
   immatriculation VARCHAR(50)
);

CREATE TABLE FACTURE(
   idFacture VARCHAR(50) PRIMARY KEY NOT NULL,
   idCompte VARCHAR(30) NOT NULL
);

CREATE TABLE PECHE(
   idBateau VARCHAR(50) NOT NULL,
   datePeche DATE NOT NULL,
   PRIMARY KEY(idBateau, datePeche)
);

CREATE TABLE LOT(
   idBateau VARCHAR(50) NOT NULL,
   datePeche DATE NOT NULL,
   idLot INT NOT NULL,
   idEspece VARCHAR(50) NOT NULL,
   idTaille INT NOT NULL,
   idPresentation VARCHAR(50) NOT NULL,
   idBac VARCHAR(50) NOT NULL,
   idCompte VARCHAR(30) NOT NULL,
   idQualite VARCHAR(50) NOT NULL,
   poidsBrutLot DECIMAL(15,2),
   prixPlancher DECIMAL(15,2),
   prixDepart DECIMAL(15,2),
   prixEncheresMax DECIMAL(15,2),
   dateEnchere DATE,
   heureDebutEnchere TIME,
   codeEtat VARCHAR(50),
   idFacture VARCHAR(50) NOT NULL,
   PRIMARY KEY(idBateau, datePeche, idLot)
);

CREATE TABLE ANNONCE(
   idBateau VARCHAR(50) NOT NULL,
   datePeche DATE NOT NULL,
   idLot INT NOT NULL,
   prixEnchere DECIMAL(15,2),
   heureEnchere TIME,
   nomAnnonce VARCHAR(50),
   idCompteV VARCHAR(30) NOT NULL,
   PRIMARY KEY(idBateau, datePeche, idLot, idCompteA)
);

-- Ajout des contraintes de clés étrangères
ALTER TABLE ACHETEUR
ADD CONSTRAINT fk_acheteur_compte
FOREIGN KEY (idCompte) 
REFERENCES COMPTE(idCompte);

ALTER TABLE VENDEUR
ADD CONSTRAINT fk_vendeur_compte
FOREIGN KEY (idCompte) 
REFERENCES COMPTE(idCompte);

ALTER TABLE ADMIN
ADD CONSTRAINT fk_admin_compte
FOREIGN KEY (idCompte) 
REFERENCES COMPTE(idCompte);

ALTER TABLE FACTURE
ADD CONSTRAINT fk_facture_acheteur
FOREIGN KEY (idCompte) 
REFERENCES ACHETEUR(idCompte);

ALTER TABLE PECHE
ADD CONSTRAINT fk_peche_bateau
FOREIGN KEY (idBateau) 
REFERENCES BATEAU(idBateau);

ALTER TABLE LOT
ADD CONSTRAINT fk_lot_peche
FOREIGN KEY (idBateau, datePeche) 
REFERENCES PECHE(idBateau, datePeche);

ALTER TABLE LOT
ADD CONSTRAINT fk_lot_espece
FOREIGN KEY (idEspece) 
REFERENCES ESPECE(idEspece);

ALTER TABLE LOT
ADD CONSTRAINT fk_lot_taille
FOREIGN KEY (idTaille) 
REFERENCES TAILLE(idTaille);

ALTER TABLE LOT
ADD CONSTRAINT fk_lot_presentation
FOREIGN KEY (idPresentation) 
REFERENCES PRESENTATION(idPresentation);

ALTER TABLE LOT
ADD CONSTRAINT fk_lot_bac
FOREIGN KEY (idBac) 
REFERENCES BAC(idBac);

ALTER TABLE LOT
ADD CONSTRAINT fk_lot_acheteur
FOREIGN KEY (idCompte) 
REFERENCES ACHETEUR(idCompte);

ALTER TABLE LOT
ADD CONSTRAINT fk_lot_qualite
FOREIGN KEY (idQualite) 
REFERENCES QUALITE(idQualite);

ALTER TABLE LOT
ADD CONSTRAINT fk_lot_facture
FOREIGN KEY (idFacture) 
REFERENCES FACTURE(idFacture);

ALTER TABLE ANNONCE
ADD CONSTRAINT fk_annonce_lot
FOREIGN KEY (idBateau, datePeche, idLot) 
REFERENCES LOT(idBateau, datePeche, idLot);

ALTER TABLE ANNONCE
ADD CONSTRAINT fk_annonce_vendeur
FOREIGN KEY (idCompteV) 
REFERENCES VENDEUR(idCompte);

-- Insertion des données de base
INSERT INTO QUALITE (idQualite, libelle)
VALUES 
('E', 'extra'),
('A', 'glacé'),
('B', 'déclassé');

INSERT INTO PRESENTATION (idPresentation, libelle)
VALUES 
('ENT', 'entier'),
('VID', 'vidé');

INSERT INTO BAC (idBac, tare)
VALUES
('B', 2.50),
('F', 4.00);

INSERT INTO ESPECE (idEspece, nomEspece, nomCommun, nomScientifique)
VALUES
('LJL', 'LJAUL', 'lieu jaune ligne', 'polliachus polliachus');

INSERT INTO TAILLE (idTaille, specification)
VALUES
(10, 'taille 1'),
(20, 'taille 2'),
(30, 'taille 3'),
(40, 'taille 4'),
(50, 'taille 5');

INSERT INTO BATEAU (idBateau, immatriculation)
VALUES
('KORRI', 'AA 123456');