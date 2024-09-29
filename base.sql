CREATE TABLE cout_unitaire (
    id_cout_unitaire INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    unite_oeuvre VARCHAR(50),
    prix_unitaire DECIMAL(10, 2) CHECK (prix_unitaire >= 0)     
)
CREATE TABLE charge (
    id_charge INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    unite_oeuvre VARCHAR(50),
    nature BOOLEAN,
    montant DECIMAL(10, 2) CHECK (montant >= 0),
    /* 
    quantite DECIMAL(10, 2) CHECK (quantite >= 0),
    id_cout_unitaire INT DEFAULT -1,
    FOREIGN KEY (id_cout_unitaire) REFERENCES cout_unitaire(id_cout_unitaire) */
);

CREATE TABLE centre (
    id_centre INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    operative BOOLEAN NOT NULL
);

CREATE TABLE placement_charge (
    id_placement INT PRIMARY KEY AUTO_INCREMENT,
    id_charge INT NOT NULL,
    montant DECIMAL(10, 2) CHECK (montant >= 0),
    date DATE NOT NULL,
    FOREIGN KEY (id_charge) REFERENCES charge(id_charge)
);

CREATE TABLE repartition_charge_centre (
    id_repartition_charge_centre INT PRIMARY KEY AUTO_INCREMENT,
    id_centre INT NOT NULL,
    id_charge INT NOT NULL,
    pourcentage DECIMAL(5, 2) CHECK (pourcentage >= 0 AND pourcentage <= 100),
    date DATE NOT NULL,
    FOREIGN KEY (id_centre) REFERENCES centre(id_centre),
    FOREIGN KEY (id_charge) REFERENCES charge(id_charge)
);

CREATE TABLE repartition_structure_operative (
    id_repartition_structure_operation INT PRIMARY KEY AUTO_INCREMENT,
    id_centre_operative INT NOT NULL,
    id_centre_structure INT NOT NULL,
    pourcentage DECIMAL(5, 2) CHECK (pourcentage >= 0 AND pourcentage <= 100),
    date DATE NOT NULL,
    FOREIGN KEY (id_centre_operative) REFERENCES centre(id_centre),
    FOREIGN KEY (id_centre_structure) REFERENCES centre(id_centre)
);

CREATE TABLE produit (
    id_produit INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE production_centre (
    id_production_centre INT PRIMARY KEY AUTO_INCREMENT,
    id_produit INT NOT NULL,
    id_centre INT NOT NULL,
    date_fin_production DATE,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit),
    FOREIGN KEY (id_centre) REFERENCES centre(id_centre)
);
CREATE TABLE exercice (
    id_exercice INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL
)

