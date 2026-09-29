CREATE DATABASE IF NOT EXISTS touche_pas_au_klaxon
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE touche_pas_au_klaxon;

-- Schemas
CREATE TABLE IF NOT EXISTS agencies (
    id INT UNSIGNED AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
	telephone VARCHAR(15) NOT NULL,
	email VARCHAR(255) NOT NULL UNIQUE,
	mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS trips (
    id INT UNSIGNED AUTO_INCREMENT,
	gdh_depart DATETIME NOT NULL,
	gdh_arrivee DATETIME NOT NULL,
	places_disponibles INT UNSIGNED NOT NULL,
	agence_depart_id INT UNSIGNED NOT NULL,
	agence_arrivee_id INT UNSIGNED NOT NULL,
	users_id INT UNSIGNED NOT NULL,
    PRIMARY KEY(id),
    	CONSTRAINT fk_agence_depart_id
    	FOREIGN KEY (agence_depart_id) REFERENCES agencies (id)
    	ON DELETE RESTRICT,
		CONSTRAINT fk_agence_arrivee_id
    	FOREIGN KEY (agence_arrivee_id) REFERENCES agencies (id)
    	ON DELETE RESTRICT,
	CONSTRAINT fk_users
    	FOREIGN KEY (users_id) REFERENCES users (id)
    	ON DELETE RESTRICT
);