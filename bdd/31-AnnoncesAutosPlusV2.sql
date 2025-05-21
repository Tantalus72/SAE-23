-- creation de la tables ANNONCES
CREATE TABLE annonces (
idAnnonce INTEGER PRIMARY KEY  NOT NULL ,
idMarque VARCHAR(5),
idModele VARCHAR(5),
designation VARCHAR(100), 
annee INTEGER,
kilometrage INTEGER,
prix INTEGER,
CONSTRAINT fk_Marque FOREIGN KEY (idMarque) REFERENCES marques (idMarque) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_Modele FOREIGN KEY (idModele) REFERENCES modeles (idModele) ON DELETE CASCADE ON UPDATE CASCADE
	
);

-- creation de la table MARQUES
CREATE TABLE marques (
idMarque VARCHAR(5) PRIMARY KEY  NOT NULL ,
nom VARCHAR(50)
);

-- creation de la table MODELES
CREATE TABLE modeles (
idModele VARCHAR(5) PRIMARY KEY  NOT NULL ,
idMarque VARCHAR(5),
nom VARCHAR(50)
);