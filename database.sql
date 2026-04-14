CREATE DATABASE gestion_formation;

USE gestion_formation;

CREATE TABLE formation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    description TEXT,
    domaine VARCHAR(100),
    niveau VARCHAR(100),
    prix DECIMAL(10,2),
    duree VARCHAR(50),
    instructor VARCHAR(100),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);