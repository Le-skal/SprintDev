	-- Création de la base de données
	-- Only create

	CREATE DATABASE IF NOT EXISTS sprintdev;
	USE sprintdev;


-- Table Livres
CREATE TABLE Livres (
    ID INT PRIMARY KEY,
    Titre VARCHAR(255) NOT NULL,
    Auteur VARCHAR(255),  -- On pourrait aussi avoir une table Auteur séparée et utiliser une clé étrangère ici
    Categorie VARCHAR(100), -- De même, une table Categorie serait plus normalisée
    Etat VARCHAR(50)  -- (disponible, emprunté, réservé) - On pourrait utiliser un ENUM pour une meilleure intégrité
);

-- Table Utilisateurs
CREATE TABLE Utilisateurs (
    ID INT PRIMARY KEY,
    Nom VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Role VARCHAR(50) NOT NULL,  -- (administrateur, bibliothécaire, utilisateur) - Encore une fois, un ENUM serait préférable
    DateInscription DATE,
    MotDePasse VARCHAR(255) NOT NULL -- Important de stocker les mots de passe de manière sécurisée (hachage)
);

-- Table Emprunts
CREATE TABLE Emprunts (
    ID INT PRIMARY KEY,
    Utilisateur_ID INT,
    Livre_ID INT,
    DateEmprunt DATE,
    DateEcheance DATE,
    Retard VARCHAR(50), 
    FOREIGN KEY (Utilisateur_ID) REFERENCES Utilisateurs(ID),
    FOREIGN KEY (Livre_ID) REFERENCES Livres(ID)
);

-- Table Reservations
CREATE TABLE Reservations (
    ID INT PRIMARY KEY,
    Utilisateur_ID INT,
    Livre_ID INT,
    DateReservation DATE,
    FOREIGN KEY (Utilisateur_ID) REFERENCES Utilisateurs(ID),
    FOREIGN KEY (Livre_ID) REFERENCES Livres(ID)
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
INSERT INTO Utilisateurs (ID, Nom, Email, Role, DateInscription, MotDePasse)
VALUES 
    ('user', 'user@gmail.com', 'User', '2024-11-25', 'user'),
    ('admin', 'admin@gmail.com', 'User', '2024-11-25', 'admin'),
    ('bib', 'bib@gmail.com', 'User', '2024-11-25', 'bib'),
    ('Jean Dupont', 'jean.dupont@example.com', 'User', '2024-01-15', 'password1'),
    ('Marie Curie', 'marie.curie@example.com', 'User', '2024-02-20', 'password2'),
    ('Paul Verlaine', 'paul.verlaine@example.com', 'User', '2023-11-10', 'password3'),
    ('Alice Martin', 'alice.martin@example.com', 'User', '2022-09-05', 'password4'),
    ('Émile Zola', 'emile.zola@example.com', 'User', '2024-04-12', 'password5');
