-- Last Update: 2024/01/29 14:00

CREATE TABLE Livre(
   idLivre VARCHAR(50),
   titre VARCHAR(100) NOT NULL,
   dateSortie DATE NOT NULL,
   langue VARCHAR(50) NOT NULL,
   couvertureLink VARCHAR(150) NOT NULL,
   PRIMARY KEY(idLivre)
);

CREATE TABLE Categorie(
   idCat VARCHAR(50),
   nom VARCHAR(50) NOT NULL,
   description VARCHAR(255) NOT NULL,
   PRIMARY KEY(idCat)
);

CREATE TABLE Auteur(
   idAut VARCHAR(50),
   nom VARCHAR(50) NOT NULL,
   prenom VARCHAR(50) NOT NULL,
   dateNaiss DATE,
   dateDeces DATE,
   nationalite VARCHAR(50),
   photoLink VARCHAR(150) NOT NULL,
   description TEXT NOT NULL,
   PRIMARY KEY(idAut)
);

CREATE TABLE Role(
   idRole VARCHAR(50),
   name VARCHAR(50) NOT NULL,
   permissionRang TINYINT NOT NULL,
   PRIMARY KEY(idRole),
   UNIQUE(name)
);

CREATE TABLE Adherent(
   idAdherent VARCHAR(50),
   dateAdhesion DATE NOT NULL,
   nom VARCHAR(50) NOT NULL,
   prenom VARCHAR(50) NOT NULL,
   dateNaiss DATE NOT NULL,
   email VARCHAR(50) NOT NULL,
   adressePostale VARCHAR(80) NOT NULL,
   numTel VARCHAR(10) NOT NULL,
   photoLink VARCHAR(250) NOT NULL,
   password VARCHAR(255) NOT NULL,
   idRole VARCHAR(50),
   PRIMARY KEY(idAdherent),
   FOREIGN KEY(idRole) REFERENCES Role(idRole)
);

CREATE TABLE Reservation(
   idResa VARCHAR(50),
   dateResa DATETIME NOT NULL,
   idLivre VARCHAR(50) NOT NULL,
   idAdherent VARCHAR(50) NOT NULL,
   PRIMARY KEY(idResa),
   FOREIGN KEY(idLivre) REFERENCES Livre(idLivre),
   FOREIGN KEY(idAdherent) REFERENCES Adherent(idAdherent)
);

CREATE TABLE Emprunt(
   idEmp VARCHAR(50),
   dateEmprunt DATE NOT NULL,
   dateRetour DATE NOT NULL,
   dateRetourLimite DATE NOT NULL,
   idLivre VARCHAR(50) NOT NULL,
   idAdherent VARCHAR(50) NOT NULL,
   PRIMARY KEY(idEmp),
   FOREIGN KEY(idLivre) REFERENCES Livre(idLivre),
   FOREIGN KEY(idAdherent) REFERENCES Adherent(idAdherent)
);

CREATE TABLE Appartenir(
   idLivre VARCHAR(50),
   idCat VARCHAR(50),
   PRIMARY KEY(idLivre, idCat),
   FOREIGN KEY(idLivre) REFERENCES Livre(idLivre),
   FOREIGN KEY(idCat) REFERENCES Categorie(idCat)
);

CREATE TABLE Ecrire(
   idLivre VARCHAR(50),
   idAut VARCHAR(50),
   PRIMARY KEY(idLivre, idAut),
   FOREIGN KEY(idLivre) REFERENCES Livre(idLivre),
   FOREIGN KEY(idAut) REFERENCES Auteur(idAut)
);
