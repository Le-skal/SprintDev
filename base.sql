	-- Création de la base de données
	-- Only create

	CREATE DATABASE IF NOT EXISTS sprintdev;
	USE sprintdev;


-- Table Livres
CREATE TABLE Livres (
    ID INT(11) NOT NULL AUTO_INCREMENT,
    Titre VARCHAR(255) NOT NULL,
    Auteur VARCHAR(255) DEFAULT NULL,
    Categorie VARCHAR(100) DEFAULT NULL,
    Etat VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (ID)
);

-- Table Utilisateurs
CREATE TABLE Utilisateurs (
    ID INT(11) NOT NULL AUTO_INCREMENT,
    Nom VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    Role VARCHAR(50) NOT NULL,
    DateInscription DATE NOT NULL,
    MotDePasse VARCHAR(255) NOT NULL,
    PRIMARY KEY (ID)
);

-- Table Emprunts
CREATE TABLE Emprunts (
    ID INT(11) NOT NULL AUTO_INCREMENT,
    Utilisateur_ID INT(11) DEFAULT NULL,
    Livre_ID INT(11) DEFAULT NULL,
    DateEmprunt DATE DEFAULT NULL,
    DateEcheance DATE DEFAULT NULL,
    Retard TINYINT(1) DEFAULT NULL,
    PRIMARY KEY (ID)
);


-- Table Reservations
CREATE TABLE Reservations (
    ID INT(11) NOT NULL AUTO_INCREMENT,
    Utilisateur_ID INT(11) DEFAULT NULL,
    Livre_ID INT(11) DEFAULT NULL,
    DateReservation DATE DEFAULT NULL,
    PRIMARY KEY (ID)
);

-- Insertion de données dans la table Livres
INSERT INTO Livres (Titre, Auteur, Categorie, Etat)
VALUES 
    ('Les Misérables', 'Victor Hugo', 'Fiction', 'Disponible'),
    ('1984', 'George Orwell', 'Non-Fiction', 'Disponible'),
    ('L’Étranger', 'Albert Camus', 'Science', 'Disponible'),
    ('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Histoire', 'Disponible'),
    ('Don Quichotte', 'Miguel de Cervantes', 'Non-fiction', 'Disponible');

-- Insertion de données dans la table Utilisateurs
INSERT INTO Utilisateurs (Nom, Email, Role, DateInscription, MotDePasse)
VALUES 
    ('user', 'user@gmail.com', 'User', '2024-11-25', 'user'),
    ('admin', 'admin@gmail.com', 'Admin', '2024-11-25', 'admin'),
    ('bib', 'bib@gmail.com', 'Bibliothecaire', '2024-11-25', 'bib'),
    ('Jean Dupont', 'jean.dupont@example.com', 'User', '2024-01-15', 'password1'),
    ('Marie Curie', 'marie.curie@example.com', 'User', '2024-02-20', 'password2'),
    ('Paul Verlaine', 'paul.verlaine@example.com', 'User', '2023-11-10', 'password3'),
    ('Alice Martin', 'alice.martin@example.com', 'User', '2022-09-05', 'password4'),
    ('Émile Zola', 'emile.zola@example.com', 'User', '2024-04-12', 'password5');
