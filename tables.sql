-- Table promotions
CREATE TABLE promotions (
    id_promo INT PRIMARY KEY,
    formation VARCHAR(50),
    annee_forma INT
);

-- Table ue
CREATE TABLE ue (
    id_ue INT PRIMARY KEY,
    intitule VARCHAR(50),
    id_promo INT,
    FOREIGN KEY (id_promo) REFERENCES promotions(id_promo)
);

-- Table ressource
CREATE TABLE ressource (
    id_ressource INT PRIMARY KEY,
    intitule VARCHAR(50),
    ue INT,
    FOREIGN KEY (ue) REFERENCES ue(id_ue)
);

-- Table eval
CREATE TABLE eval (
    id_eval INT PRIMARY KEY,
    id_ressource INT,
    coeff FLOAT,
    intitule VARCHAR(50),
    date DATETIME,
    FOREIGN KEY (id_ressource) REFERENCES ressource(id_ressource)
);

-- Table etudiant
CREATE TABLE etudiant (
    id_etud INT PRIMARY KEY,
    tp VARCHAR(1)
);

-- Table enseignants
CREATE TABLE enseignants (
    id_ens INT PRIMARY KEY,
    num_tel INT,
    mail VARCHAR(50)
);

-- Table notes
CREATE TABLE notes (
    id_eval INT,
    id_etud INT,
    note INT,
    PRIMARY KEY (id_eval, id_etud),
    FOREIGN KEY (id_eval) REFERENCES eval(id_eval),
    FOREIGN KEY (id_etud) REFERENCES etudiant(id_etud),
);

-- Table Compte
CREATE TABLE Compte (
    id INT PRIMARY KEY,
    nom VARCHAR(30),
    prenom VARCHAR(30),
    password TEXT,
    niv_perm INT
);

-- Table matiereEns
CREATE TABLE matiereEns (
    id_ressource INT,
    id_ens INT,
    PRIMARY KEY (id_ressource, id_ens),
    FOREIGN KEY (id_ressource) REFERENCES ressource(id_ressource),
    FOREIGN KEY (id_ens) REFERENCES enseignants(id_ens)
);

-- Table tp
CREATE TABLE tp (
    id_tp INT PRIMARY KEY,
    libelle VARCHAR(30),
    promotion INT,
    FOREIGN KEY (promotion) REFERENCES promotions(id_promo)
);
