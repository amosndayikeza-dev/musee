-- Sauvegarde du 2026-06-25 00:07:19



-- Structure de la table audit_logs
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `table_cible` varchar(50) DEFAULT NULL,
  `enregistrement_id` int(11) DEFAULT NULL,
  `anciennes_valeurs` text DEFAULT NULL,
  `nouvelles_valeurs` text DEFAULT NULL,
  `ip_adresse` varchar(45) DEFAULT NULL,
  `date_action` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table audit_logs
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('1', '7', 'admin@musee.com', 'INSERT', 'oeuvre', '30', NULL, '{\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"auteur_id\":\"8\",\"categorie_id\":\"62\",\"statut\":\"en restauration\",\"photo\":\"uploads\\/oeuvres\\/6a38f8e91aecb.jpg\"}', '::1', '2026-06-22 09:57:13');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('2', '7', 'admin@musee.com', 'INSERT', 'oeuvre', '31', NULL, '{\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"auteur_id\":\"8\",\"categorie_id\":\"62\",\"statut\":\"en restauration\",\"photo\":\"uploads\\/oeuvres\\/6a38f8fa3c0b9.jpg\"}', '::1', '2026-06-22 09:57:30');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('3', '7', 'admin@musee.com', 'INSERT', 'oeuvre', '32', NULL, '{\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"auteur_id\":\"8\",\"categorie_id\":\"62\",\"statut\":\"en restauration\",\"photo\":\"uploads\\/oeuvres\\/6a38f9057855f.jpg\"}', '::1', '2026-06-22 09:57:42');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('4', '7', 'admin@musee.com', 'INSERT', 'oeuvre', '33', NULL, '{\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"auteur_id\":\"8\",\"categorie_id\":\"62\",\"statut\":\"en restauration\",\"photo\":\"uploads\\/oeuvres\\/6a38f99771d30.jpg\"}', '::1', '2026-06-22 10:00:07');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('5', '7', 'admin@musee.com', 'INSERT', 'oeuvre', '34', NULL, '{\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"auteur_id\":\"8\",\"categorie_id\":\"62\",\"statut\":\"en restauration\",\"photo\":\"uploads\\/oeuvres\\/6a38fb820395e.jpg\"}', '::1', '2026-06-22 10:08:18');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('6', '7', 'admin@musee.com', 'ARCHIVE', 'oeuvre', '34', '{\"id\":34,\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"photo\":\"uploads\\/oeuvres\\/6a38fb820395e.jpg\",\"auteur_id\":8,\"categorie_id\":62,\"statut\":\"en restauration\",\"archive\":0}', '{\"archive\":1}', '::1', '2026-06-22 10:55:55');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('7', '7', 'admin@musee.com', 'ARCHIVE', 'oeuvre', '34', '{\"id\":34,\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"photo\":\"uploads\\/oeuvres\\/6a38fb820395e.jpg\",\"auteur_id\":8,\"categorie_id\":62,\"statut\":\"en restauration\",\"archive\":1}', '{\"archive\":1}', '::1', '2026-06-22 10:56:17');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('8', '7', 'admin@musee.com', 'DELETE', 'exposition', '6', '{\"id\":6,\"titre\":\"Ordinateur\",\"description\":\"Ordinateur portable\",\"date_debut\":\"2026-06-08\",\"date_fin\":\"2026-06-12\",\"lieu\":\"Salle D\",\"statut\":\"en cours\",\"archive\":0}', NULL, '::1', '2026-06-22 11:10:52');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('9', '7', 'admin@musee.com', 'ARCHIVE', 'oeuvre', '34', '{\"id\":34,\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"photo\":\"uploads\\/oeuvres\\/6a38fb820395e.jpg\",\"auteur_id\":8,\"categorie_id\":62,\"statut\":\"en restauration\",\"archive\":1}', '{\"archive\":1}', '::1', '2026-06-22 11:11:10');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('10', '7', 'admin@musee.com', 'ARCHIVE', 'oeuvre', '34', '{\"id\":34,\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"photo\":\"uploads\\/oeuvres\\/6a38fb820395e.jpg\",\"auteur_id\":8,\"categorie_id\":62,\"statut\":\"en restauration\",\"archive\":1}', '{\"archive\":1}', '::1', '2026-06-22 11:18:13');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('11', '7', 'admin@musee.com', 'ARCHIVE', 'oeuvre', '34', '{\"id\":34,\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"photo\":\"uploads\\/oeuvres\\/6a38fb820395e.jpg\",\"auteur_id\":8,\"categorie_id\":62,\"statut\":\"en restauration\",\"archive\":1}', '{\"archive\":1}', '::1', '2026-06-22 11:18:28');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('12', '7', 'admin@musee.com', 'UNARCHIVE', 'oeuvre', '34', '{\"id\":34,\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"photo\":\"uploads\\/oeuvres\\/6a38fb820395e.jpg\",\"auteur_id\":8,\"categorie_id\":62,\"statut\":\"en restauration\",\"archive\":1}', '{\"archive\":0}', '::1', '2026-06-22 11:22:08');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('13', '7', 'admin@musee.com', 'ARCHIVE', 'oeuvre', '34', '{\"id\":34,\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"photo\":\"uploads\\/oeuvres\\/6a38fb820395e.jpg\",\"auteur_id\":8,\"categorie_id\":62,\"statut\":\"en restauration\",\"archive\":0}', '{\"archive\":1}', '::1', '2026-06-22 11:22:35');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('14', '7', 'admin@musee.com', 'INSERT', 'theme', '5', NULL, '{\"nom\":\"NDAYIKEZA\",\"couleur_primaire\":\"#517aa4\",\"couleur_secondaire\":\"#cda947\",\"couleur_fond\":\"#5475a6\",\"couleur_texte\":\"#333333\",\"actif\":\"1\"}', '::1', '2026-06-22 15:45:48');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('15', '7', 'admin@musee.com', 'UPDATE', 'theme', '4', '{\"id\":5,\"nom\":\"NDAYIKEZA\",\"couleur_primaire\":\"#517aa4\",\"couleur_secondaire\":\"#cda947\",\"couleur_fond\":\"#5475a6\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 15:46:02');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('16', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":4,\"nom\":\"Bleu\",\"couleur_primaire\":\"#0d47a1\",\"couleur_secondaire\":\"#42a5f5\",\"couleur_fond\":\"#e3f2fd\",\"couleur_texte\":\"#0d47a1\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 15:46:30');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('17', '7', 'admin@musee.com', 'UPDATE', 'theme', '3', '{\"id\":3,\"nom\":\"Sombre\",\"couleur_primaire\":\"#2c2c2c\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#1a1a1a\",\"couleur_texte\":\"#ffffff\",\"actif\":0}', '{\"nom\":\"Sombre\",\"couleur_primaire\":\"#f00f0f\",\"couleur_secondaire\":\"#0d0d0c\",\"couleur_fond\":\"#1a1a1a\",\"couleur_texte\":\"#ffffff\",\"actif\":\"0\"}', '::1', '2026-06-22 15:46:56');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('18', '7', 'admin@musee.com', 'UPDATE', 'theme', '3', '{\"id\":1,\"nom\":\"D\\u00e9faut\",\"couleur_primaire\":\"#1a2a3a\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#f4f6f9\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 15:47:01');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('19', '7', 'admin@musee.com', 'UPDATE', 'theme', '5', '{\"id\":3,\"nom\":\"Sombre\",\"couleur_primaire\":\"#f00f0f\",\"couleur_secondaire\":\"#0d0d0c\",\"couleur_fond\":\"#1a1a1a\",\"couleur_texte\":\"#ffffff\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 15:57:00');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('20', '7', 'admin@musee.com', 'UPDATE', 'theme', '3', '{\"id\":5,\"nom\":\"NDAYIKEZA\",\"couleur_primaire\":\"#517aa4\",\"couleur_secondaire\":\"#cda947\",\"couleur_fond\":\"#5475a6\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 16:00:07');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('21', '7', 'admin@musee.com', 'UPDATE', 'theme', '4', '{\"id\":3,\"nom\":\"Sombre\",\"couleur_primaire\":\"#f00f0f\",\"couleur_secondaire\":\"#0d0d0c\",\"couleur_fond\":\"#1a1a1a\",\"couleur_texte\":\"#ffffff\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 16:00:14');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('22', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":4,\"nom\":\"Bleu\",\"couleur_primaire\":\"#0d47a1\",\"couleur_secondaire\":\"#42a5f5\",\"couleur_fond\":\"#e3f2fd\",\"couleur_texte\":\"#0d47a1\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 16:00:19');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('23', '7', 'admin@musee.com', 'UPDATE', 'theme', '5', '{\"id\":1,\"nom\":\"D\\u00e9faut\",\"couleur_primaire\":\"#1a2a3a\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#f4f6f9\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 16:01:27');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('24', '7', 'admin@musee.com', 'UPDATE', 'theme', '3', '{\"id\":5,\"nom\":\"NDAYIKEZA\",\"couleur_primaire\":\"#517aa4\",\"couleur_secondaire\":\"#cda947\",\"couleur_fond\":\"#5475a6\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 16:01:31');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('25', '7', 'admin@musee.com', 'UPDATE', 'theme', '4', '{\"id\":3,\"nom\":\"Sombre\",\"couleur_primaire\":\"#f00f0f\",\"couleur_secondaire\":\"#0d0d0c\",\"couleur_fond\":\"#1a1a1a\",\"couleur_texte\":\"#ffffff\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 16:06:53');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('26', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":4,\"nom\":\"Bleu\",\"couleur_primaire\":\"#0d47a1\",\"couleur_secondaire\":\"#42a5f5\",\"couleur_fond\":\"#e3f2fd\",\"couleur_texte\":\"#0d47a1\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 16:07:03');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('27', '7', 'admin@musee.com', 'UPDATE', 'theme', '5', '{\"id\":1,\"nom\":\"D\\u00e9faut\",\"couleur_primaire\":\"#1a2a3a\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#f4f6f9\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 16:07:17');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('28', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":5,\"nom\":\"NDAYIKEZA\",\"couleur_primaire\":\"#517aa4\",\"couleur_secondaire\":\"#cda947\",\"couleur_fond\":\"#5475a6\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 16:07:27');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('29', '7', 'admin@musee.com', 'UPDATE', 'categorie', '69', '[true]', '{\"nom\":\"Art brut\",\"description\":\"\\u0152uvres r\\u00e9alis\\u00e9es par des autodidactes\"}', '::1', '2026-06-22 20:34:47');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('30', '7', 'admin@musee.com', 'UPDATE', 'theme', '3', '{\"id\":1,\"nom\":\"D\\u00e9faut\",\"couleur_primaire\":\"#1a2a3a\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#f4f6f9\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 21:24:10');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('31', '7', 'admin@musee.com', 'UPDATE', 'theme', '4', '{\"id\":3,\"nom\":\"Sombre\",\"couleur_primaire\":\"#f00f0f\",\"couleur_secondaire\":\"#0d0d0c\",\"couleur_fond\":\"#1a1a1a\",\"couleur_texte\":\"#ffffff\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 21:24:20');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('32', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":4,\"nom\":\"Bleu\",\"couleur_primaire\":\"#0d47a1\",\"couleur_secondaire\":\"#42a5f5\",\"couleur_fond\":\"#e3f2fd\",\"couleur_texte\":\"#0d47a1\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 21:25:18');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('33', '7', 'admin@musee.com', 'UPDATE', 'theme', '4', '{\"id\":1,\"nom\":\"D\\u00e9faut\",\"couleur_primaire\":\"#1a2a3a\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#f4f6f9\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 21:25:24');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('34', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":4,\"nom\":\"Bleu\",\"couleur_primaire\":\"#0d47a1\",\"couleur_secondaire\":\"#42a5f5\",\"couleur_fond\":\"#e3f2fd\",\"couleur_texte\":\"#0d47a1\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-22 21:26:34');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('35', '7', 'admin@musee.com', 'DELETE', 'oeuvre', '30', '{\"id\":30,\"titre\":\"Cahier\",\"description\":\"Ouvre d\'art\",\"date_creation\":\"2026-06-08\",\"technique\":\"Claude\",\"dimensions\":\"52\",\"photo\":\"uploads\\/oeuvres\\/6a38f8e91aecb.jpg\",\"auteur_id\":8,\"categorie_id\":62,\"statut\":\"en restauration\",\"archive\":0,\"deleted_at\":null}', NULL, '::1', '2026-06-23 12:04:38');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('36', '7', 'admin@musee.com', 'DELETE', 'categorie', '71', '[true]', NULL, '::1', '2026-06-23 15:46:52');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('37', '7', 'admin@musee.com', 'DELETE', 'categorie', '70', '[true]', NULL, '::1', '2026-06-23 15:57:36');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('38', '7', 'admin@musee.com', 'UPDATE', 'theme', '2', '{\"id\":1,\"nom\":\"D\\u00e9faut\",\"couleur_primaire\":\"#1a2a3a\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#f4f6f9\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 09:00:45');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('39', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":2,\"nom\":\"Clair\",\"couleur_primaire\":\"#ffffff\",\"couleur_secondaire\":\"#2c3e50\",\"couleur_fond\":\"#ecf0f1\",\"couleur_texte\":\"#2c3e50\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 09:00:50');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('40', '7', 'admin@musee.com', 'UPDATE', 'theme', '5', '{\"id\":1,\"nom\":\"D\\u00e9faut\",\"couleur_primaire\":\"#1a2a3a\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#f4f6f9\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 09:00:57');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('41', '7', 'admin@musee.com', 'UPDATE', 'theme', '3', '{\"id\":5,\"nom\":\"NDAYIKEZA\",\"couleur_primaire\":\"#517aa4\",\"couleur_secondaire\":\"#cda947\",\"couleur_fond\":\"#5475a6\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 09:01:05');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('42', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":3,\"nom\":\"Sombre\",\"couleur_primaire\":\"#f00f0f\",\"couleur_secondaire\":\"#0d0d0c\",\"couleur_fond\":\"#1a1a1a\",\"couleur_texte\":\"#ffffff\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 09:01:09');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('43', '7', 'admin@musee.com', 'UPDATE', 'exposition', '7', '{\"id\":7,\"titre\":\"Exposition Test\",\"description\":\"Test pour v\\u00e9rifier le comptage\",\"date_debut\":\"2026-06-18\",\"date_fin\":\"2026-06-24\",\"lieu\":\"Salle de test\",\"statut\":\"en cours\",\"archive\":0,\"deleted_at\":null,\"photo\":null}', '{\"titre\":\"Exposition Test\",\"description\":\"Test pour v\\u00e9rifier le comptage\",\"date_debut\":\"2026-06-18\",\"date_fin\":\"2026-06-24\",\"lieu\":\"Salle de test\",\"statut\":\"en cours\"}', '::1', '2026-06-24 09:34:33');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('44', '7', 'admin@musee.com', 'UPDATE', 'exposition', '1', '{\"id\":1,\"titre\":\"Impressionnisme fran\\u00e7ais\",\"description\":\"Exposition des grands impressionnistes\",\"date_debut\":\"2025-06-01\",\"date_fin\":\"2025-08-31\",\"lieu\":\"Salle A\",\"statut\":\"termin\\u00e9e\",\"archive\":0,\"deleted_at\":null,\"photo\":null}', '{\"titre\":\"Impressionnisme fran\\u00e7ais\",\"description\":\"Exposition des grands impressionnistes\",\"date_debut\":\"2025-06-01\",\"date_fin\":\"2025-08-31\",\"lieu\":\"Salle A\",\"statut\":\"termin\\u00e9e\"}', '::1', '2026-06-24 09:34:54');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('45', '7', 'admin@musee.com', 'INSERT', 'exposition', '8', NULL, '{\"titre\":\"Burundian Art\",\"description\":\"Burundi traditional\",\"date_debut\":\"2026-06-08\",\"date_fin\":\"2026-06-17\",\"lieu\":\"Salle 2\",\"statut\":\"en cours\"}', '::1', '2026-06-24 09:53:08');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('46', '7', 'admin@musee.com', 'UPDATE', 'theme', '4', '{\"id\":1,\"nom\":\"D\\u00e9faut\",\"couleur_primaire\":\"#1a2a3a\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#f4f6f9\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 10:07:26');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('47', '7', 'admin@musee.com', 'UPDATE', 'theme', '3', '{\"id\":4,\"nom\":\"Bleu\",\"couleur_primaire\":\"#0d47a1\",\"couleur_secondaire\":\"#42a5f5\",\"couleur_fond\":\"#e3f2fd\",\"couleur_texte\":\"#0d47a1\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 10:07:29');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('48', '7', 'admin@musee.com', 'UPDATE', 'theme', '5', '{\"id\":3,\"nom\":\"Sombre\",\"couleur_primaire\":\"#f00f0f\",\"couleur_secondaire\":\"#0d0d0c\",\"couleur_fond\":\"#1a1a1a\",\"couleur_texte\":\"#ffffff\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 10:07:32');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('49', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":5,\"nom\":\"NDAYIKEZA\",\"couleur_primaire\":\"#517aa4\",\"couleur_secondaire\":\"#cda947\",\"couleur_fond\":\"#5475a6\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 10:07:34');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('50', '7', 'admin@musee.com', 'UPDATE', 'theme', '2', '{\"id\":1,\"nom\":\"D\\u00e9faut\",\"couleur_primaire\":\"#1a2a3a\",\"couleur_secondaire\":\"#c9a84c\",\"couleur_fond\":\"#f4f6f9\",\"couleur_texte\":\"#333333\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 10:07:37');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('51', '7', 'admin@musee.com', 'UPDATE', 'theme', '2', '{\"id\":2,\"nom\":\"Clair\",\"couleur_primaire\":\"#ffffff\",\"couleur_secondaire\":\"#2c3e50\",\"couleur_fond\":\"#ecf0f1\",\"couleur_texte\":\"#2c3e50\",\"actif\":1}', '{\"nom\":\"Clair\",\"couleur_primaire\":\"#ffffff\",\"couleur_secondaire\":\"#2a69a7\",\"couleur_fond\":\"#5c95a3\",\"couleur_texte\":\"#308ce8\",\"actif\":\"1\"}', '::1', '2026-06-24 10:08:00');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('52', '7', 'admin@musee.com', 'UPDATE', 'theme', '3', '{\"id\":2,\"nom\":\"Clair\",\"couleur_primaire\":\"#ffffff\",\"couleur_secondaire\":\"#2a69a7\",\"couleur_fond\":\"#5c95a3\",\"couleur_texte\":\"#308ce8\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 10:08:09');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('53', '7', 'admin@musee.com', 'UPDATE', 'theme', '1', '{\"id\":3,\"nom\":\"Sombre\",\"couleur_primaire\":\"#f00f0f\",\"couleur_secondaire\":\"#0d0d0c\",\"couleur_fond\":\"#1a1a1a\",\"couleur_texte\":\"#ffffff\",\"actif\":1}', '{\"actif\":1}', '::1', '2026-06-24 10:08:17');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('54', '7', 'admin@musee.com', 'INSERT', 'exposition', '9', NULL, '{\"titre\":\"Ordinateur\",\"description\":\"DeskTop\",\"date_debut\":\"2026-06-02\",\"date_fin\":\"2026-06-02\",\"lieu\":\"Salle D\",\"statut\":\"termin\\u00e9e\",\"photo\":\"uploads\\/expositions\\/6a3ba3047a9c2.jpg\"}', '::1', '2026-06-24 10:27:32');
INSERT INTO `audit_logs` (`id`, `utilisateur_id`, `email`, `action`, `table_cible`, `enregistrement_id`, `anciennes_valeurs`, `nouvelles_valeurs`, `ip_adresse`, `date_action`) VALUES ('55', '7', 'admin@musee.com', 'INSERT', 'auteur', '105', NULL, '{\"nom\":\"Talon\",\"prenom\":\"Talon\",\"biographie\":\"Oeuvre d\'Art\",\"date_naissance\":\"2026-06-01\",\"date_deces\":\"2026-06-04\",\"nationalite\":\"congolais\",\"matricule\":\"AUT-2026-002\",\"photo\":\"uploads\\/expositions\\/6a3ba41188938.jpg\"}', '::1', '2026-06-24 10:32:01');



-- Structure de la table auteurs
DROP TABLE IF EXISTS `auteurs`;
CREATE TABLE `auteurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matricule` varchar(20) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `biographie` text DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `date_deces` date DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `archive` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricule` (`matricule`),
  KEY `idx_auteur_nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table auteurs
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('1', 'AUT-2025-001', 'Monet', 'Claude', 'Peintre impressionniste français', '1840-11-14', NULL, 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('2', 'AUT-2025-002', 'Rodin', 'Auguste', 'Sculpteur français', '1840-11-12', NULL, 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('3', 'AUT-2025-003', 'Picasso', 'Pablo', 'Peintre, sculpteur espagnol', '1881-10-25', '2024-11-06', 'Espagnole', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('5', 'AUT-2026-001', 'Jean Babptiste', 'KOKO', 'Auteur', '2025-10-28', '2026-06-09', 'congolais', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('6', 'AUT-2024-001', 'Picasso', 'Pablo', 'Peintre, sculpteur, graveur espagnol. Figure majeure du cubisme.', '1881-10-25', '1973-04-08', 'Espagnole', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('7', 'AUT-2024-002', 'Van Gogh', 'Vincent', 'Peintre post-impressionniste néerlandais. Célèbre pour ses couleurs vives et son style expressif.', '1853-03-30', '1890-07-29', 'Néerlandaise', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('8', 'AUT-2024-003', 'Monet', 'Claude', 'Peintre français, fondateur de l\'impressionnisme.', '1840-11-14', '1926-12-05', 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('9', 'AUT-2024-004', 'Rodin', 'Auguste', 'Sculpteur français, considéré comme le père de la sculpture moderne.', '1840-11-12', '1917-11-17', 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('10', 'AUT-2024-005', 'Da Vinci', 'Léonard', 'Peintre, sculpteur, inventeur, architecte, musicien et scientifique italien.', '1452-04-15', '1519-05-02', 'Italienne', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('11', 'AUT-2024-006', 'Rembrandt', 'Harmenszoon van Rijn', 'Peintre et graveur néerlandais, l\'un des plus grands artistes de l\'histoire.', '1606-07-15', '1669-10-04', 'Néerlandaise', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('12', 'AUT-2024-007', 'Cézanne', 'Paul', 'Peintre français, précurseur du cubisme et de l\'art moderne.', '1839-01-19', '1906-10-22', 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('13', 'AUT-2024-008', 'Gauguin', 'Eugène Henri Paul', 'Peintre post-impressionniste français, célèbre pour ses œuvres tahitiennes.', '1848-06-07', '1903-05-08', 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('14', 'AUT-2024-009', 'Matisse', 'Henri', 'Peintre, dessinateur et sculpteur français, chef de file du fauvisme.', '1869-12-31', '1954-11-03', 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('15', 'AUT-2024-010', 'Klimt', 'Gustav', 'Peintre symboliste autrichien, figure majeure de la Sécession viennoise.', '1862-07-14', '1918-02-06', 'Autrichienne', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('16', 'AUT-2024-011', 'Munch', 'Edvard', 'Peintre et graveur norvégien, précurseur de l\'expressionnisme.', '1863-12-12', '1944-01-23', 'Norvégienne', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('17', 'AUT-2024-012', 'Goya', 'Francisco de', 'Peintre espagnol, précurseur du romantisme.', '1746-03-30', '1828-04-16', 'Espagnole', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('18', 'AUT-2024-013', 'Vermeer', 'Johannes', 'Peintre néerlandais, maître de la peinture de genre.', '1632-10-31', '1675-12-15', 'Néerlandaise', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('19', 'AUT-2024-014', 'Manet', 'Édouard', 'Peintre français, figure majeure de la transition entre le réalisme et l\'impressionnisme.', '1832-01-23', '1883-04-30', 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('20', 'AUT-2024-015', 'Renoir', 'Pierre-Auguste', 'Peintre et sculpteur français, l\'un des plus célèbres impressionnistes.', '1841-02-25', '1919-12-03', 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('21', 'AUT-2024-016', 'Caravage', 'Michelangelo Merisi da', 'Peintre italien, maître du clair-obscur.', '1571-09-29', '1610-07-18', 'Italienne', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('22', 'AUT-2024-017', 'Degas', 'Edgar', 'Peintre, sculpteur et graveur français, célèbre pour ses danseuses.', '1834-07-19', '1917-09-27', 'Française', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('23', 'AUT-2024-018', 'Kahlo', 'Frida', 'Peintre mexicaine, célèbre pour ses autoportraits.', '1907-07-06', '1954-07-13', 'Mexicaine', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('24', 'AUT-2024-019', 'Dali', 'Salvador', 'Peintre espagnol, figure majeure du surréalisme.', '1904-05-11', '1989-01-23', 'Espagnole', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('25', 'AUT-2024-020', 'Rothko', 'Mark', 'Peintre américain, figure majeure de l\'expressionnisme abstrait.', '1903-09-25', '1970-02-25', 'Américaine', '0', NULL, NULL);
INSERT INTO `auteurs` (`id`, `matricule`, `nom`, `prenom`, `biographie`, `date_naissance`, `date_deces`, `nationalite`, `archive`, `deleted_at`, `photo`) VALUES ('105', 'AUT-2026-002', 'Talon', 'Talon', 'Oeuvre d\'Art', '2026-06-01', '2026-06-04', 'congolais', '0', NULL, 'uploads/expositions/6a3ba41188938.jpg');



-- Structure de la table categorie
DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table categorie
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('1', 'Peinture', 'Œuvres picturales', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('2', 'Sculpture', 'Œuvres en trois dimensions', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('3', 'Photographie', 'Images photographiques', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('4', 'Arts graphiques', 'Dessins, gravures', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('5', 'Oeuvre d\'Art', 'Tableau Burundais', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('58', 'Installation', 'Œuvres d\'art contemporain en trois dimensions.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('59', 'Céramique', 'Œuvres en terre cuite, porcelaine ou faïence.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('60', 'Tapisserie', 'Œuvres textiles tissées ou brodées.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('61', 'Mosaïque', 'Œuvres composées de fragments de pierre, verre ou céramique.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('62', 'Fresque', 'Peinture murale réalisée sur enduit frais.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('63', 'Enluminure', 'Peinture ornementale sur parchemin ou vélin.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('64', 'Collage', 'Œuvre composée d\'éléments collés sur un support.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('65', 'Art numérique', 'Œuvres créées à l\'aide de technologies numériques.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('66', 'Art conceptuel', 'Œuvre où l\'idée prime sur l\'objet.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('67', 'Performance', 'Œuvre d\'art vivante, réalisée devant un public.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('68', 'Land Art', 'Œuvre d\'art réalisée dans le paysage naturel.', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('69', 'Art brut', 'Œuvres réalisées par des autodidactes', NULL);
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('70', 'Art déco', 'Style artistique des années 1920-1930.', '2026-06-23 15:57:32');
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('71', 'Art nouveau', 'Mouvement artistique de la fin du XIXe siècle.', '2026-06-23 15:46:52');
INSERT INTO `categorie` (`id`, `nom`, `description`, `deleted_at`) VALUES ('72', 'NDAYIKEZA', '', NULL);



-- Structure de la table commentaires
DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oeuvre_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL,
  `est_approuve` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `oeuvre_id` (`oeuvre_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`oeuvre_id`) REFERENCES `oeuvre` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Structure de la table exposition_oeuvre
DROP TABLE IF EXISTS `exposition_oeuvre`;
CREATE TABLE `exposition_oeuvre` (
  `exposition_id` int(11) NOT NULL,
  `oeuvre_id` int(11) NOT NULL,
  `date_arrivee` date DEFAULT NULL,
  `date_depart` date DEFAULT NULL,
  PRIMARY KEY (`exposition_id`,`oeuvre_id`),
  KEY `oeuvre_id` (`oeuvre_id`),
  CONSTRAINT `exposition_oeuvre_ibfk_1` FOREIGN KEY (`exposition_id`) REFERENCES `expositions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exposition_oeuvre_ibfk_2` FOREIGN KEY (`oeuvre_id`) REFERENCES `oeuvre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table exposition_oeuvre
INSERT INTO `exposition_oeuvre` (`exposition_id`, `oeuvre_id`, `date_arrivee`, `date_depart`) VALUES ('2', '2', '2025-07-10', '2025-10-20');
INSERT INTO `exposition_oeuvre` (`exposition_id`, `oeuvre_id`, `date_arrivee`, `date_depart`) VALUES ('3', '3', '2025-08-20', '2025-12-25');
INSERT INTO `exposition_oeuvre` (`exposition_id`, `oeuvre_id`, `date_arrivee`, `date_depart`) VALUES ('5', '3', '2026-06-19', NULL);
INSERT INTO `exposition_oeuvre` (`exposition_id`, `oeuvre_id`, `date_arrivee`, `date_depart`) VALUES ('8', '32', '2026-06-24', NULL);
INSERT INTO `exposition_oeuvre` (`exposition_id`, `oeuvre_id`, `date_arrivee`, `date_depart`) VALUES ('9', '31', '2026-06-24', NULL);



-- Structure de la table expositions
DROP TABLE IF EXISTS `expositions`;
CREATE TABLE `expositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `lieu` varchar(200) DEFAULT NULL,
  `statut` enum('prévue','en cours','terminée') DEFAULT 'prévue',
  `archive` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_exposition_date` (`date_debut`,`date_fin`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table expositions
INSERT INTO `expositions` (`id`, `titre`, `description`, `date_debut`, `date_fin`, `lieu`, `statut`, `archive`, `deleted_at`, `photo`) VALUES ('1', 'Impressionnisme français', 'Exposition des grands impressionnistes', '2025-06-01', '2025-08-31', 'Salle A', 'terminée', '0', NULL, NULL);
INSERT INTO `expositions` (`id`, `titre`, `description`, `date_debut`, `date_fin`, `lieu`, `statut`, `archive`, `deleted_at`, `photo`) VALUES ('2', 'Sculptures modernes', 'Exposition de sculptures du XXe siècle', '2025-07-15', '2025-10-15', 'Salle B', 'en cours', '0', NULL, NULL);
INSERT INTO `expositions` (`id`, `titre`, `description`, `date_debut`, `date_fin`, `lieu`, `statut`, `archive`, `deleted_at`, `photo`) VALUES ('3', 'Picasso et ses contemporains', 'Rétrospective Picasso', '2025-09-01', '2025-12-20', 'Salle C', 'prévue', '0', NULL, NULL);
INSERT INTO `expositions` (`id`, `titre`, `description`, `date_debut`, `date_fin`, `lieu`, `statut`, `archive`, `deleted_at`, `photo`) VALUES ('5', 'rrr', 'rrr', '2026-06-09', '2026-06-14', 'Salle A', 'en cours', '0', NULL, NULL);
INSERT INTO `expositions` (`id`, `titre`, `description`, `date_debut`, `date_fin`, `lieu`, `statut`, `archive`, `deleted_at`, `photo`) VALUES ('7', 'Exposition Test', 'Test pour vérifier le comptage', '2026-06-18', '2026-06-24', 'Salle de test', 'en cours', '0', NULL, NULL);
INSERT INTO `expositions` (`id`, `titre`, `description`, `date_debut`, `date_fin`, `lieu`, `statut`, `archive`, `deleted_at`, `photo`) VALUES ('8', 'Burundian Art', 'Burundi traditional', '2026-06-08', '2026-06-17', 'Salle 2', 'en cours', '0', NULL, NULL);
INSERT INTO `expositions` (`id`, `titre`, `description`, `date_debut`, `date_fin`, `lieu`, `statut`, `archive`, `deleted_at`, `photo`) VALUES ('9', 'Ordinateur', 'DeskTop', '2026-06-02', '2026-06-02', 'Salle D', 'terminée', '0', NULL, 'uploads/expositions/6a3ba3047a9c2.jpg');



-- Structure de la table historique_connexions
DROP TABLE IF EXISTS `historique_connexions`;
CREATE TABLE `historique_connexions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `ip_adresse` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `date_connexion` datetime DEFAULT current_timestamp(),
  `statut` enum('succès','échec','déconnexion') DEFAULT 'succès',
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `historique_connexions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table historique_connexions
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('1', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 07:56:02', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('2', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 09:13:22', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('3', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 10:53:17', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('4', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 10:53:33', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('5', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 15:12:39', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('6', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 20:33:25', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('7', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:04:57', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('8', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:05:33', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('9', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:12:28', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('10', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:12:49', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('11', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:13:33', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('12', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:22:20', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('13', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:23:22', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('14', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:23:39', 'échec');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('15', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:23:57', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('16', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:25:32', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('17', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:25:46', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('18', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:25:53', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('19', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 21:26:11', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('20', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-23 09:11:51', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('21', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-23 10:38:48', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('22', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-23 15:26:20', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('23', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-23 16:53:29', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('24', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 07:30:31', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('25', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 07:56:44', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('26', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 08:30:45', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('27', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 09:51:20', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('28', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 10:13:29', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('29', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 10:13:57', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('30', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 12:03:39', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('31', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 12:03:57', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('32', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 12:10:51', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('33', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 13:05:46', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('34', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 13:13:59', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('35', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 13:14:13', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('36', '14', 'obede@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 13:15:02', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('37', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 13:15:13', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('38', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 15:55:14', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('39', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 16:47:28', 'déconnexion');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('40', '7', 'admin@musee.com', '192.168.137.215', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-06-24 16:49:18', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('41', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 16:52:50', 'succès');
INSERT INTO `historique_connexions` (`id`, `utilisateur_id`, `email`, `ip_adresse`, `user_agent`, `date_connexion`, `statut`) VALUES ('42', '7', 'admin@musee.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 23:05:50', 'succès');



-- Structure de la table messages_chat
DROP TABLE IF EXISTS `messages_chat`;
CREATE TABLE `messages_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expediteur_id` int(11) NOT NULL,
  `destinataire_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `est_lu` tinyint(1) DEFAULT 0,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `fichier` varchar(255) DEFAULT NULL,
  `type_fichier` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expediteur_id` (`expediteur_id`),
  KEY `destinataire_id` (`destinataire_id`),
  CONSTRAINT `messages_chat_ibfk_1` FOREIGN KEY (`expediteur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_chat_ibfk_2` FOREIGN KEY (`destinataire_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table messages_chat
INSERT INTO `messages_chat` (`id`, `expediteur_id`, `destinataire_id`, `message`, `est_lu`, `date_envoi`, `fichier`, `type_fichier`) VALUES ('1', '7', '14', 'Hi Obede', '1', '2026-06-24 12:00:48', 'uploads/chat/6a3bb8e057e65.jpg', 'image/jpeg');
INSERT INTO `messages_chat` (`id`, `expediteur_id`, `destinataire_id`, `message`, `est_lu`, `date_envoi`, `fichier`, `type_fichier`) VALUES ('2', '7', '8', 'hi', '0', '2026-06-24 12:03:00', NULL, NULL);
INSERT INTO `messages_chat` (`id`, `expediteur_id`, `destinataire_id`, `message`, `est_lu`, `date_envoi`, `fichier`, `type_fichier`) VALUES ('3', '7', '8', 'how are doing?', '0', '2026-06-24 12:03:14', NULL, NULL);
INSERT INTO `messages_chat` (`id`, `expediteur_id`, `destinataire_id`, `message`, `est_lu`, `date_envoi`, `fichier`, `type_fichier`) VALUES ('4', '14', '7', 'oui, Admin j\'ai bien recu le message. Et puis concernant notre rapport , je vous enverra le resultat', '1', '2026-06-24 12:05:35', NULL, NULL);
INSERT INTO `messages_chat` (`id`, `expediteur_id`, `destinataire_id`, `message`, `est_lu`, `date_envoi`, `fichier`, `type_fichier`) VALUES ('5', '7', '14', 'Bon apres', '1', '2026-06-24 13:10:52', NULL, NULL);
INSERT INTO `messages_chat` (`id`, `expediteur_id`, `destinataire_id`, `message`, `est_lu`, `date_envoi`, `fichier`, `type_fichier`) VALUES ('6', '7', '14', '', '1', '2026-06-24 13:13:49', 'uploads/chat/6a3bc9fd78e1d.pdf', 'application/pdf');
INSERT INTO `messages_chat` (`id`, `expediteur_id`, `destinataire_id`, `message`, `est_lu`, `date_envoi`, `fichier`, `type_fichier`) VALUES ('7', '7', '8', 'hhhht', '0', '2026-06-24 13:15:43', NULL, NULL);
INSERT INTO `messages_chat` (`id`, `expediteur_id`, `destinataire_id`, `message`, `est_lu`, `date_envoi`, `fichier`, `type_fichier`) VALUES ('8', '7', '8', 'jhgf', '0', '2026-06-24 13:15:53', NULL, NULL);



-- Structure de la table messages_contact
DROP TABLE IF EXISTS `messages_contact`;
CREATE TABLE `messages_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `est_lu` tinyint(1) DEFAULT 0,
  `repondu` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_lu` (`est_lu`),
  KEY `idx_date` (`date_envoi`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table messages_contact
INSERT INTO `messages_contact` (`id`, `nom`, `email`, `sujet`, `message`, `date_envoi`, `est_lu`, `repondu`) VALUES ('1', 'Amos NDAYIKEZA', 'amosndayikeza@gmail.com', 'Demande de l\'horaire du musee pour les visites', 'Hi, i am writting you from Bujumbura', '2026-06-23 11:27:21', '0', '0');
INSERT INTO `messages_contact` (`id`, `nom`, `email`, `sujet`, `message`, `date_envoi`, `est_lu`, `repondu`) VALUES ('2', 'Amos NDAYIKEZA', 'amosndayikeza@gmail.com', 'Demande de l\'horaire du musee pour les visites', 'Hi, i am writting you from Bujumbura', '2026-06-23 11:27:37', '0', '0');
INSERT INTO `messages_contact` (`id`, `nom`, `email`, `sujet`, `message`, `date_envoi`, `est_lu`, `repondu`) VALUES ('3', 'NDAYIKEZA', 'devopsamos@gmail.com', 'ttttttttttttttttttttt', 'tttttttttttttttttttttttt', '2026-06-23 11:37:00', '1', '0');
INSERT INTO `messages_contact` (`id`, `nom`, `email`, `sujet`, `message`, `date_envoi`, `est_lu`, `repondu`) VALUES ('4', 'NDAYIKEZA', 'devopsamos@gmail.com', 'ttttttttttttttttttttt', 'tttttttttttttttttttttttt', '2026-06-23 11:42:15', '0', '0');
INSERT INTO `messages_contact` (`id`, `nom`, `email`, `sujet`, `message`, `date_envoi`, `est_lu`, `repondu`) VALUES ('5', 'Agur', 'agur@gmail.com', 'Demande', 'Bonjour', '2026-06-24 08:34:14', '1', '0');
INSERT INTO `messages_contact` (`id`, `nom`, `email`, `sujet`, `message`, `date_envoi`, `est_lu`, `repondu`) VALUES ('6', 'Agur', 'agur@gmail.com', 'Demande', 'Bonjour', '2026-06-24 08:34:41', '1', '0');



-- Structure de la table mouvement
DROP TABLE IF EXISTS `mouvement`;
CREATE TABLE `mouvement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oeuvre_id` int(11) NOT NULL,
  `type` enum('entrée','sortie') NOT NULL,
  `date` date NOT NULL,
  `provenance` varchar(200) DEFAULT NULL,
  `destination` varchar(200) DEFAULT NULL,
  `responsable` varchar(100) DEFAULT NULL,
  `archive` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oeuvre_id` (`oeuvre_id`),
  CONSTRAINT `mouvement_ibfk_1` FOREIGN KEY (`oeuvre_id`) REFERENCES `oeuvre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table mouvement
INSERT INTO `mouvement` (`id`, `oeuvre_id`, `type`, `date`, `provenance`, `destination`, `responsable`, `archive`, `deleted_at`) VALUES ('2', '4', 'sortie', '2025-05-01', 'Musée National', 'Musée d\'Orsay', 'Sophie Lefevre', '0', NULL);



-- Structure de la table notifications
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `lien` varchar(255) DEFAULT NULL,
  `est_lu` tinyint(1) DEFAULT 0,
  `date_creation` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Structure de la table oeuvre
DROP TABLE IF EXISTS `oeuvre`;
CREATE TABLE `oeuvre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `date_creation` date DEFAULT NULL,
  `technique` varchar(100) DEFAULT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `auteur_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `statut` enum('exposé','en réserve','en restauration','en prêt') DEFAULT 'en réserve',
  `archive` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auteur_id` (`auteur_id`),
  KEY `categorie_id` (`categorie_id`),
  KEY `idx_oeuvre_titre` (`titre`),
  KEY `idx_oeuvre_statut` (`statut`),
  CONSTRAINT `oeuvre_ibfk_1` FOREIGN KEY (`auteur_id`) REFERENCES `auteurs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `oeuvre_ibfk_2` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table oeuvre
INSERT INTO `oeuvre` (`id`, `titre`, `description`, `date_creation`, `technique`, `dimensions`, `photo`, `auteur_id`, `categorie_id`, `statut`, `archive`, `deleted_at`) VALUES ('2', 'Le Penseur', 'Statue en bronze', '1902-01-01', 'Bronze', '180x120x90', NULL, '2', '2', 'exposé', '0', NULL);
INSERT INTO `oeuvre` (`id`, `titre`, `description`, `date_creation`, `technique`, `dimensions`, `photo`, `auteur_id`, `categorie_id`, `statut`, `archive`, `deleted_at`) VALUES ('3', 'Guernica', 'Fresque', '1937-01-01', 'Huile', '350x780', NULL, '3', '1', 'en réserve', '0', NULL);
INSERT INTO `oeuvre` (`id`, `titre`, `description`, `date_creation`, `technique`, `dimensions`, `photo`, `auteur_id`, `categorie_id`, `statut`, `archive`, `deleted_at`) VALUES ('4', 'Portrait de Dora Maar', 'Portrait', '1937-01-01', 'Huile', '55x46', NULL, '3', '1', 'exposé', '0', NULL);
INSERT INTO `oeuvre` (`id`, `titre`, `description`, `date_creation`, `technique`, `dimensions`, `photo`, `auteur_id`, `categorie_id`, `statut`, `archive`, `deleted_at`) VALUES ('5', 'ttttttttttt', 'tttttttttttt', '2026-06-08', 'ggggggggggggg', '25', 'uploads/oeuvres/6a33ec0f6e458.jpg', '1', '1', 'en restauration', '0', NULL);
INSERT INTO `oeuvre` (`id`, `titre`, `description`, `date_creation`, `technique`, `dimensions`, `photo`, `auteur_id`, `categorie_id`, `statut`, `archive`, `deleted_at`) VALUES ('30', 'Cahier', 'Ouvre d\'art', '2026-06-08', 'Claude', '52', 'uploads/oeuvres/6a38f8e91aecb.jpg', '8', '62', 'en restauration', '0', '2026-06-23 12:04:37');
INSERT INTO `oeuvre` (`id`, `titre`, `description`, `date_creation`, `technique`, `dimensions`, `photo`, `auteur_id`, `categorie_id`, `statut`, `archive`, `deleted_at`) VALUES ('31', 'Cahier', 'Ouvre d\'art', '2026-06-08', 'Claude', '52', 'uploads/oeuvres/6a38f8fa3c0b9.jpg', '8', '62', 'en restauration', '0', NULL);
INSERT INTO `oeuvre` (`id`, `titre`, `description`, `date_creation`, `technique`, `dimensions`, `photo`, `auteur_id`, `categorie_id`, `statut`, `archive`, `deleted_at`) VALUES ('32', 'Cahier', 'Ouvre d\'art', '2026-06-08', 'Claude', '52', 'uploads/oeuvres/6a38f9057855f.jpg', '8', '62', 'en restauration', '0', NULL);
INSERT INTO `oeuvre` (`id`, `titre`, `description`, `date_creation`, `technique`, `dimensions`, `photo`, `auteur_id`, `categorie_id`, `statut`, `archive`, `deleted_at`) VALUES ('33', 'Cahier', 'Ouvre d\'art', '2026-06-08', 'Claude', '52', 'uploads/oeuvres/6a38f99771d30.jpg', '8', '62', 'en restauration', '0', NULL);
INSERT INTO `oeuvre` (`id`, `titre`, `description`, `date_creation`, `technique`, `dimensions`, `photo`, `auteur_id`, `categorie_id`, `statut`, `archive`, `deleted_at`) VALUES ('34', 'Cahier', 'Ouvre d\'art', '2026-06-08', 'Claude', '52', 'uploads/oeuvres/6a38fb820395e.jpg', '8', '62', 'en restauration', '1', NULL);



-- Structure de la table parametres
DROP TABLE IF EXISTS `parametres`;
CREATE TABLE `parametres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cle` varchar(100) NOT NULL,
  `valeur` text DEFAULT NULL,
  `type` varchar(50) DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL,
  `date_modification` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `cle` (`cle`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table parametres
INSERT INTO `parametres` (`id`, `cle`, `valeur`, `type`, `description`, `date_modification`) VALUES ('1', 'site_name', 'Musée National', 'text', 'Nom du site', '2026-06-21 17:46:26');
INSERT INTO `parametres` (`id`, `cle`, `valeur`, `type`, `description`, `date_modification`) VALUES ('2', 'site_email', 'gitega@museenational.bi', 'email', 'Email de contact', '2026-06-22 20:38:38');
INSERT INTO `parametres` (`id`, `cle`, `valeur`, `type`, `description`, `date_modification`) VALUES ('3', 'site_phone', '+257 66642122', 'text', 'Numéro de téléphone', '2026-06-22 20:38:38');
INSERT INTO `parametres` (`id`, `cle`, `valeur`, `type`, `description`, `date_modification`) VALUES ('4', 'site_address', '1 Rue du Musée, 75001 Gitega', 'text', 'Adresse du musée', '2026-06-22 20:38:38');
INSERT INTO `parametres` (`id`, `cle`, `valeur`, `type`, `description`, `date_modification`) VALUES ('5', 'timeout_session', '3600', 'number', 'Durée de session en secondes', '2026-06-21 17:46:26');
INSERT INTO `parametres` (`id`, `cle`, `valeur`, `type`, `description`, `date_modification`) VALUES ('6', 'max_attempts', '5', 'number', 'Nombre de tentatives de connexion avant verrouillage', '2026-06-21 17:46:26');
INSERT INTO `parametres` (`id`, `cle`, `valeur`, `type`, `description`, `date_modification`) VALUES ('7', 'notifications_email', '1', 'boolean', 'Activer les notifications par email', '2026-06-21 17:46:26');
INSERT INTO `parametres` (`id`, `cle`, `valeur`, `type`, `description`, `date_modification`) VALUES ('8', 'maintenance_mode', '0', 'boolean', 'Mode maintenance', '2026-06-21 17:46:26');



-- Structure de la table prets
DROP TABLE IF EXISTS `prets`;
CREATE TABLE `prets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oeuvre_id` int(11) NOT NULL,
  `emprunteur` varchar(200) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` enum('en cours','retourné') DEFAULT 'en cours',
  `observations` text DEFAULT NULL,
  `archive` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oeuvre_id` (`oeuvre_id`),
  CONSTRAINT `prets_ibfk_1` FOREIGN KEY (`oeuvre_id`) REFERENCES `oeuvre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table prets
INSERT INTO `prets` (`id`, `oeuvre_id`, `emprunteur`, `date_debut`, `date_fin`, `statut`, `observations`, `archive`, `deleted_at`) VALUES ('1', '4', 'Musée d\'Orsay', '2025-05-01', '2025-08-01', 'retourné', 'Prêt pour exposition temporaire', '0', NULL);



-- Structure de la table reset_password_tokens
DROP TABLE IF EXISTS `reset_password_tokens`;
CREATE TABLE `reset_password_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_expiration` datetime DEFAULT NULL,
  `utilise` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_token` (`token`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Structure de la table restauration
DROP TABLE IF EXISTS `restauration`;
CREATE TABLE `restauration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oeuvre_id` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `responsable` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cout` decimal(10,2) DEFAULT NULL,
  `archive` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oeuvre_id` (`oeuvre_id`),
  CONSTRAINT `restauration_ibfk_1` FOREIGN KEY (`oeuvre_id`) REFERENCES `oeuvre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table restauration
INSERT INTO `restauration` (`id`, `oeuvre_id`, `date_debut`, `date_fin`, `responsable`, `description`, `cout`, `archive`, `deleted_at`) VALUES ('1', '3', '2025-04-01', NULL, 'Marie Dupont', 'Nettoyage et restauration de la toile', '1500.00', '0', NULL);
INSERT INTO `restauration` (`id`, `oeuvre_id`, `date_debut`, `date_fin`, `responsable`, `description`, `cout`, `archive`, `deleted_at`) VALUES ('2', '2', '2026-06-08', '2026-06-12', 'VIOLO', 'tttttt', '20000.00', '0', NULL);



-- Structure de la table themes
DROP TABLE IF EXISTS `themes`;
CREATE TABLE `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `couleur_primaire` varchar(7) DEFAULT '#1a2a3a',
  `couleur_secondaire` varchar(7) DEFAULT '#c9a84c',
  `couleur_fond` varchar(7) DEFAULT '#f4f6f9',
  `couleur_texte` varchar(7) DEFAULT '#333333',
  `actif` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table themes
INSERT INTO `themes` (`id`, `nom`, `couleur_primaire`, `couleur_secondaire`, `couleur_fond`, `couleur_texte`, `actif`) VALUES ('1', 'Défaut', '#1a2a3a', '#c9a84c', '#f4f6f9', '#333333', '1');
INSERT INTO `themes` (`id`, `nom`, `couleur_primaire`, `couleur_secondaire`, `couleur_fond`, `couleur_texte`, `actif`) VALUES ('2', 'Clair', '#ffffff', '#2a69a7', '#5c95a3', '#308ce8', '0');
INSERT INTO `themes` (`id`, `nom`, `couleur_primaire`, `couleur_secondaire`, `couleur_fond`, `couleur_texte`, `actif`) VALUES ('3', 'Sombre', '#f00f0f', '#0d0d0c', '#1a1a1a', '#ffffff', '0');
INSERT INTO `themes` (`id`, `nom`, `couleur_primaire`, `couleur_secondaire`, `couleur_fond`, `couleur_texte`, `actif`) VALUES ('4', 'Bleu', '#0d47a1', '#42a5f5', '#e3f2fd', '#0d47a1', '0');
INSERT INTO `themes` (`id`, `nom`, `couleur_primaire`, `couleur_secondaire`, `couleur_fond`, `couleur_texte`, `actif`) VALUES ('5', 'NDAYIKEZA', '#517aa4', '#cda947', '#5475a6', '#333333', '0');



-- Structure de la table utilisateurs
DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `biographie` text DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `code_postal` varchar(20) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `genre` enum('homme','femme','autre') DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','conservateur','visiteur') DEFAULT 'visiteur',
  `date_creation` datetime DEFAULT current_timestamp(),
  `dernier_acces` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données de la table utilisateurs
INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `telephone`, `photo`, `biographie`, `adresse`, `ville`, `code_postal`, `pays`, `date_naissance`, `genre`, `email`, `mot_de_passe`, `role`, `date_creation`, `dernier_acces`, `deleted_at`) VALUES ('1', 'Admin', 'System', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-06-18 10:36:52', NULL, NULL);
INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `telephone`, `photo`, `biographie`, `adresse`, `ville`, `code_postal`, `pays`, `date_naissance`, `genre`, `email`, `mot_de_passe`, `role`, `date_creation`, `dernier_acces`, `deleted_at`) VALUES ('7', 'Administrateur', 'Admin', '', 'uploads/profils/user_7_1782112067.jpg', '', '', '', '', '', NULL, '', 'admin@musee.com', '$2y$10$e8j.7Trfvnb.040hqx.fWe/laAjtANjVtVLzzYuqvwxrq2HCFOZMK', 'admin', '2026-06-18 12:50:38', '2026-06-24 23:05:51', NULL);
INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `telephone`, `photo`, `biographie`, `adresse`, `ville`, `code_postal`, `pays`, `date_naissance`, `genre`, `email`, `mot_de_passe`, `role`, `date_creation`, `dernier_acces`, `deleted_at`) VALUES ('8', 'Gusenga', 'Obed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'gusenga.obede@test.com', '$2y$10$H.P.GK9HbSu3SHMqZ/e5iO.TmNBOfsMjvOy4zwybTC/Z8qb8oFu.W', 'visiteur', '2026-06-19 13:09:27', NULL, NULL);
INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `telephone`, `photo`, `biographie`, `adresse`, `ville`, `code_postal`, `pays`, `date_naissance`, `genre`, `email`, `mot_de_passe`, `role`, `date_creation`, `dernier_acces`, `deleted_at`) VALUES ('13', 'Martin', 'Sophie', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'conservateur@musee.com', '$2y$10$UN5E7407/CgdtzvarNBVlOHa0QuxY2j//2JoWJUiZNskUdfl8NmFq', 'conservateur', '2026-06-20 16:54:50', '2026-06-20 17:21:46', NULL);
INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `telephone`, `photo`, `biographie`, `adresse`, `ville`, `code_postal`, `pays`, `date_naissance`, `genre`, `email`, `mot_de_passe`, `role`, `date_creation`, `dernier_acces`, `deleted_at`) VALUES ('14', 'Obede', 'Obede', '+257 61616161', 'uploads/profils/user_14_1782159132.jpeg', '', 'Gatunguru', 'Bujumbura', '', 'Burundi', '2025-10-02', 'homme', 'obede@gmail.com', '$2y$10$uAR/.T5RVg8t1o1zQCDU3e6/BqwyZwLFBx27O1DpRsCRgtP6rYcVy', 'conservateur', '2026-06-21 15:42:36', '2026-06-24 13:14:13', NULL);

