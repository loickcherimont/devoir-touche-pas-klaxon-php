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


-- sample mot_de_passe : [3 first letters of prenom, capitalized][3 first letters of nom, capitalized]@test
-- ex : Alexandre Martin -> AleMar@test
--
-- Demo / development credentials only: they are committed on purpose so the
-- project can be run and reviewed out of the box. Never reuse them elsewhere
-- and never run this seed against a real database.
INSERT IGNORE INTO
    users (id, nom, prenom, telephone, email, mot_de_passe, role) 
VALUES
    (1, 'Martin', 'Alexandre', '0612345678', 'alexandre.martin@email.fr', '$2y$10$BTdfxF//cUTfoyddZ5c6Yu94AFephdtRnz6Lanb2HGHI/dteDhzs6', 'user'),
    (2, 'Dubois', 'Sophie', '0698765432', 'sophie.dubois@email.fr', '$2y$10$iSskoh8rglZnJwkgaOgDNOUY0TSXntWBX.VMIcDqtyBJccmIPxppq', 'user'),
    (3, 'Bernard', 'Julien', '0622446688', 'julien.bernard@email.fr', '$2y$10$6vB0fGN4fIY7tIsx5/wbmOHVnU280kBUjMy1dMZckxTn7O4pZhQ46', 'user'),
    (4, 'Moreau', 'Camille', '0611223344', 'camille.moreau@email.fr', '$2y$10$FPjMAxAc7QaDOlPQ8FAmeui1VX9OrcVyhNDafKu58MumbkUiclarC', 'user'),
    (5, 'Lefèvre', 'Lucie', '0777889900', 'lucie.lefevre@email.fr', '$2y$10$bMClHbgH90wmOwKFMxe0UOcm1J2H3Aw3pIrfrXPltu6ZccjsCffgW', 'user'),
    (6, 'Leroy', 'Thomas', '0655443322', 'thomas.leroy@email.fr', '$2y$10$ebik5RktNVxdZE8Eq5en0eadATCbKxyRumbQ/hC94GzZfNyoICmJS', 'user'),
    (7, 'Roux', 'Chloé', '0633221199', 'chloe.roux@email.fr', '$2y$10$YPihYzPC6.SmZe2BaClyN.xUdtw9KtrkRU3OWvWejDsq7DsssgOqS', 'user'),
    (8, 'Petit', 'Maxime', '0766778899', 'maxime.petit@email.fr', '$2y$10$hRj49IX4MYF35lKN5aE6o.R3N/fDwTcFVIa0RUhx8sSALE3TdSLdS', 'user'),
    (9, 'Garnier', 'Laura', '0688776655', 'laura.garnier@email.fr', '$2y$10$VSKg6sClRpfXRcj/0xEXqOVDTkpkCrB9LjNRdYIA9NEGCc52zF5qa', 'user'),
    (10, 'Dupuis', 'Antoine', '0744556677', 'antoine.dupuis@email.fr', '$2y$10$jcj/qJMe0KHa2qWYqU2lEeHoqvSMUzEo5f8kbfW6reFVs15VNqZ3K', 'user'),
    (11, 'Lefebvre', 'Emma', '0699887766', 'emma.lefebvre@email.fr', '$2y$10$NsNAmwaWRI3BGMMJOqoSMuoHDqv/6ivP/g8e1Iu3Z2etKRbTFpnNm', 'user'),
    (12, 'Fontaine', 'Louis', '0655667788', 'louis.fontaine@email.fr', '$2y$10$dduc4265jlJNmt2pzLE4LeUFHFToRh0rrZf.XAabI3yT68Jr69awe', 'user'),
    (13, 'Chevalier', 'Clara', '0788990011', 'clara.chevalier@email.fr', '$2y$10$ry3FJT9eqTgu5.i6.PXrCON9a6/87aQcxTQcxhoF7I/oCEkaotTVK', 'user'),
    (14, 'Robin', 'Nicolas', '0644332211', 'nicolas.robin@email.fr', '$2y$10$/f1hxv4W.oUYy.Re6kJt7eYdTAMicoTtzdNCN3N8HYjmSPR/b./2y', 'user'),
    (15, 'Gauthier', 'Marine', '0677889922', 'marine.gauthier@email.fr', '$2y$10$nXK9Z8CCbpl3sAP2VuoCp.kUIN5fwJvLohX5O4HSfq5xvAcJXj8Z6', 'user'),
    (16, 'Fournier', 'Pierre', '0722334455', 'pierre.fournier@email.fr', '$2y$10$0yEZguXGH1j39GvETqLJ1OcodVjc4pnTxH/cv5T0EL96HMPaFI6q.', 'user'),
    (17, 'Girard', 'Sarah', '0688665544', 'sarah.girard@email.fr', '$2y$10$0IVlNVoGNUvBeiOW0TwZkuv4c.LkTkq1wlrxBGn/xd5QCG8q0rcqm', 'user'),
    (18, 'Lambert', 'Hugo', '0611223366', 'hugo.lambert@email.fr', '$2y$10$YlHgX67izNH0WBESTBbiH.AxB2UoC5KnJ1xAn0eZYVtv5BXZpQGcy', 'user'),
    (19, 'Masson', 'Julie', '0733445566', 'julie.masson@email.fr', '$2y$10$qfvcLOopmRjPKpM1.RvEBecN7XQwECK07RIPY1b60M.zppT5iSZYa', 'user'),
    (20, 'Henry', 'Arthur', '0666554433', 'arthur.henry@email.fr', '$2y$10$H2hdua5XS7OywvMCW.3t6.GkOGrR.djTashUHjP5hgbyFNZtg/AxO', 'user'),
    -- John Doe -> JohDoe@test (the only admin, able to reach /admin)
    (21, 'Doe', 'John', '0123456789', 'admin@email.fr', '$2y$10$IiyetOnzjuIqhobJo87Xqe.TT0KVUxd6sBs4O/5nieJy4mWCOL/bO', 'admin');

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