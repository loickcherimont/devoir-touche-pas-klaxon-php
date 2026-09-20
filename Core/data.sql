-- We use IGNORE to avoid duplicated data on file execution

INSERT IGNORE INTO
    agencies (id, nom)
VALUES
    (1, 'Paris'),
    (2, 'Lyon'),
    (3, 'Marseille'),
    (4, 'Toulouse'),
    (5, 'Nice'),
    (6, 'Nantes'),
    (7, 'Strasbourg'),
    (8, 'Montpellier'),
    (9, 'Bordeaux'),
    (10, 'Lille'),
    (11, 'Rennes'),
    (12, 'Reims');


INSERT IGNORE INTO
    users (id, nom, prenom, telephone, email, mot_de_passe, role)
VALUES
    (1, 'Martin', 'Alexandre', '0612345678', 'alexandre.martin@email.fr', '$2y$10$placeholder_hash_01', 'user'),
    (2, 'Dubois', 'Sophie', '0698765432', 'sophie.dubois@email.fr', '$2y$10$placeholder_hash_02', 'user'),
    (3, 'Bernard', 'Julien', '0622446688', 'julien.bernard@email.fr', '$2y$10$placeholder_hash_03', 'user'),
    (4, 'Moreau', 'Camille', '0611223344', 'camille.moreau@email.fr', '$2y$10$placeholder_hash_04', 'user'),
    (5, 'Lefèvre', 'Lucie', '0777889900', 'lucie.lefevre@email.fr', '$2y$10$placeholder_hash_05', 'user'),
    (6, 'Leroy', 'Thomas', '0655443322', 'thomas.leroy@email.fr', '$2y$10$placeholder_hash_06', 'user'),
    (7, 'Roux', 'Chloé', '0633221199', 'chloe.roux@email.fr', '$2y$10$placeholder_hash_07', 'user'),
    (8, 'Petit', 'Maxime', '0766778899', 'maxime.petit@email.fr', '$2y$10$placeholder_hash_08', 'user'),
    (9, 'Garnier', 'Laura', '0688776655', 'laura.garnier@email.fr', '$2y$10$placeholder_hash_09', 'user'),
    (10, 'Dupuis', 'Antoine', '0744556677', 'antoine.dupuis@email.fr', '$2y$10$placeholder_hash_10', 'user'),
    (11, 'Lefebvre', 'Emma', '0699887766', 'emma.lefebvre@email.fr', '$2y$10$placeholder_hash_11', 'user'),
    (12, 'Fontaine', 'Louis', '0655667788', 'louis.fontaine@email.fr', '$2y$10$placeholder_hash_12', 'user'),
    (13, 'Chevalier', 'Clara', '0788990011', 'clara.chevalier@email.fr', '$2y$10$placeholder_hash_13', 'user'),
    (14, 'Robin', 'Nicolas', '0644332211', 'nicolas.robin@email.fr', '$2y$10$placeholder_hash_14', 'user'),
    (15, 'Gauthier', 'Marine', '0677889922', 'marine.gauthier@email.fr', '$2y$10$placeholder_hash_15', 'user'),
    (16, 'Fournier', 'Pierre', '0722334455', 'pierre.fournier@email.fr', '$2y$10$placeholder_hash_16', 'user'),
    (17, 'Girard', 'Sarah', '0688665544', 'sarah.girard@email.fr', '$2y$10$placeholder_hash_17', 'user'),
    (18, 'Lambert', 'Hugo', '0611223366', 'hugo.lambert@email.fr', '$2y$10$placeholder_hash_18', 'user'),
    (19, 'Masson', 'Julie', '0733445566', 'julie.masson@email.fr', '$2y$10$placeholder_hash_19', 'user'),
    (20, 'Henry', 'Arthur', '0666554433', 'arthur.henry@email.fr', '$2y$10$placeholder_hash_20', 'user');

INSERT IGNORE INTO
    trips (id, gdh_depart, gdh_arrivee, places_disponibles, agence_depart_id, agence_arrivee_id, users_id)
VALUES
    (1, '2026-09-18 14:44:00', '2026-09-19 08:00:00', 4, 3, 1, 12),
    (2, '2026-10-18 09:30:00', '2026-10-18 17:45:00', 3, 1, 9, 4),
    (3, '2026-10-19 07:00:00', '2026-10-19 14:20:00', 2, 2, 7, 8),
    (4, '2026-10-19 10:15:00', '2026-10-19 18:40:00', 5, 3, 8, 15),
    (5, '2026-10-20 08:45:00', '2026-10-20 12:30:00', 0, 6, 11, 19),
    (6, '2026-10-20 14:00:00', '2026-10-20 21:25:00', 6, 4, 2, 2),
    (7, '2026-10-21 06:30:00', '2026-10-21 11:15:00', 4, 10, 12, 6);