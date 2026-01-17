CREATE TABLE `#__batirpermi_lebatirpermis` (
  `id` int NOT NULL,
  `title` varchar(128) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` varchar(256) NOT NULL DEFAULT '',
  `cin` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `lacated` int NOT NULL DEFAULT '0',
  `image` varchar(1024) NOT NULL DEFAULT '',
  `typebatiment` varchar(256) DEFAULT NULL,
  `language` varchar(7) DEFAULT NULL,
  `state` int NOT NULL DEFAULT '1',
  `aviscomission` int DEFAULT NULL,
  `adresse` varchar(400) DEFAULT NULL,
  `superficie` int DEFAULT NULL,
  `datepermi` date DEFAULT NULL,
  `prix` float DEFAULT NULL,
  `numpermi` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;



INSERT INTO `#__batirpermi_lebatirpermis` (`id`, `title`, `created`, `nom`, `cin`, `lacated`, `image`, `typebatiment`, `language`, `state`, `aviscomission`, `adresse`, `superficie`, `datepermi`, `prix`, `numpermi`) VALUES
(1, 'dossier 1 ', '2025-06-02 18:16:50', 'nom de l\'intreressé', '123456', 0, '', ' طابق أرضي ', '*', 1, 0, 'adresse', 1500, '2025-06-22', 350.265, '12563');


ALTER TABLE `#__batirpermi_lebatirpermis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `lacated` (`lacated`),
  ADD KEY `state` (`state`);


ALTER TABLE `#__batirpermi_lebatirpermis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;




CREATE TABLE `#__batirpermi_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Categorie Id',
  `title` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Full categorie name',
  `type` varchar(1) DEFAULT NULL,
  `language` varchar(7) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `#__batirpermi_categories`
  ADD KEY `state` (`state`);



CREATE TABLE `#__batirpermi_suivies` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `reference` varchar(128) NOT NULL,
  `type` varchar(1) NOT NULL,
  `caution` varchar(5) NOT NULL,
  `created` DATETIME    NOT NULL DEFAULT '0000-00-00 00:00:00',
  `echeance` DATETIME    NOT NULL DEFAULT '0000-00-00 00:00:00',
  `datcommenc` DATETIME    NOT NULL DEFAULT '0000-00-00 00:00:00',
  `datouvoffre` DATETIME    NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` VARCHAR(1024) NOT NULL DEFAULT '',
  `support` VARCHAR(1024) NOT NULL DEFAULT '',
  `lesbatirpermis` mediumtext ,
  `downloaded` int(5)  NOT NULL DEFAULT '0',
  `lacated` int(11)    NOT NULL DEFAULT '0',
  `image` VARCHAR(1024) NOT NULL DEFAULT '',  
  `language` varchar(7) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `#__batirpermi_suivies`
  ADD KEY `title` (`title`),
  ADD KEY `lacated` (`lacated`),
  ADD KEY `state` (`state`);

