-- ============================================================
-- Smaakmaatje — databaseschema + seeddata
-- ============================================================
--
-- Bron: ERD en datadictionary uit docs/04-prototype.md §3.
--
-- Dit script:
--   1. Maakt de vijf tabellen aan (users, categories, recipes,
--      ingredients, recipe_ingredients) met de juiste FK's en ENUM's.
--   2. Vult ze met voorbeelddata om mee te ontwikkelen.
--
-- Het is IDEMPOTENT: het mag meerdere keren draaien zonder fouten.
--   * Tabellen  -> CREATE TABLE IF NOT EXISTS
--   * Rijen     -> INSERT IGNORE met vaste id's (bestaande rijen blijven staan)
-- Daarom kan het als `post-start`-hook in .ddev/config.yaml draaien.
--
-- Handmatig draaien (vanaf de host):
--   ddev mysql -uroot -proot smaakmaatje < .ddev/database/schema.sql
-- Of op een lege database (acceptatiecriterium issue #10):
--   ddev mysql -uroot -proot -e "CREATE DATABASE smaakmaatje_test"
--   ddev mysql -uroot -proot smaakmaatje_test < .ddev/database/schema.sql
-- ============================================================

SET NAMES utf8mb4;

-- ------------------------------------------------------------
-- 1. SCHEMA
-- ------------------------------------------------------------

-- users — accounts (role bepaalt of iemand de admin-module mag gebruiken)
CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT          NOT NULL AUTO_INCREMENT,
    `username`      VARCHAR(50)  NOT NULL,
    `email`         VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role`          ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_email` (`email`),
    UNIQUE KEY `uq_users_username` (`username`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- categories — de zes gerechttypes (ontbijt, lunch, diner, ...)
CREATE TABLE IF NOT EXISTS `categories` (
    `id`   INT         NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `slug` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_categories_slug` (`slug`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- recipes — de gerechten zelf
CREATE TABLE IF NOT EXISTS `recipes` (
    `id`          INT          NOT NULL AUTO_INCREMENT,
    `user_id`     INT          NOT NULL,
    `category_id` INT          NOT NULL,
    `title`       VARCHAR(100) NOT NULL,
    `description` TEXT         NULL,
    `image_url`   VARCHAR(255) NULL,
    `prep_time`   INT          NOT NULL,
    `difficulty`  ENUM('makkelijk', 'gemiddeld', 'moeilijk') NOT NULL DEFAULT 'makkelijk',
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_recipes_category` (`category_id`),
    KEY `idx_recipes_user` (`user_id`),
    CONSTRAINT `fk_recipes_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_recipes_category`
        FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- ingredients — losse ingrediënten (voor de zoekfunctie op ingrediënt)
CREATE TABLE IF NOT EXISTS `ingredients` (
    `id`   INT         NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(80) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_ingredients_name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- recipe_ingredients — koppeltabel (n:n) met de hoeveelheid per recept
CREATE TABLE IF NOT EXISTS `recipe_ingredients` (
    `recipe_id`     INT         NOT NULL,
    `ingredient_id` INT         NOT NULL,
    `amount`        VARCHAR(50) NOT NULL,
    PRIMARY KEY (`recipe_id`, `ingredient_id`),
    KEY `idx_recipe_ingredients_ingredient` (`ingredient_id`),
    CONSTRAINT `fk_recipe_ingredients_recipe`
        FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_recipe_ingredients_ingredient`
        FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- ------------------------------------------------------------
-- 2. SEEDDATA
-- ------------------------------------------------------------
-- Vaste id's zodat recepten naar de juiste categorie/gebruiker
-- kunnen verwijzen en het script herhaalbaar blijft (INSERT IGNORE).

-- Gebruikers -------------------------------------------------
-- Wachtwoord van beide demo-accounts is "welkom123" (bcrypt-hash).
INSERT IGNORE INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
    (1, 'chef_sanne', 'sanne@smaakmaatje.nl', '$2y$10$BaqH5qoUqSapFIMDEQ3m1OpoE0MTd7gGZ6wLh6.dVtZbcIy8AXpC6', 'user',  '2026-10-01 09:00:00'),
    (2, 'admin',      'admin@smaakmaatje.nl', '$2y$10$BaqH5qoUqSapFIMDEQ3m1OpoE0MTd7gGZ6wLh6.dVtZbcIy8AXpC6', 'admin', '2026-10-01 09:00:00');

-- Categorieën (de zes gerechttypes) --------------------------
INSERT IGNORE INTO `categories` (`id`, `name`, `slug`) VALUES
    (1, 'Ontbijt',      'ontbijt'),
    (2, 'Lunch',        'lunch'),
    (3, 'Diner',        'diner'),
    (4, 'Voorgerecht',  'voorgerecht'),
    (5, 'Hoofdgerecht', 'hoofdgerecht'),
    (6, 'Nagerecht',    'nagerecht');

-- Ingrediënten -----------------------------------------------
INSERT IGNORE INTO `ingredients` (`id`, `name`) VALUES
    (1,  'eieren'),
    (2,  'tomaat'),
    (3,  'brood'),
    (4,  'avocado'),
    (5,  'kaas'),
    (6,  'pasta'),
    (7,  'kipfilet'),
    (8,  'rijst'),
    (9,  'ui'),
    (10, 'knoflook'),
    (11, 'olijfolie'),
    (12, 'linzen'),
    (13, 'courgette'),
    (14, 'chocolade'),
    (15, 'bloem'),
    (16, 'melk'),
    (17, 'quinoa'),
    (18, 'pesto'),
    (19, 'tonijn'),
    (20, 'banaan'),
    (21, 'appel'),
    (22, 'suiker');

-- Recepten ---------------------------------------------------
-- image_url verwijst (tijdens de prototypefase) naar hotlink-bare
-- Unsplash-foto's; uploads/ is bedoeld voor eigen foto's van gebruikers.
INSERT IGNORE INTO `recipes`
    (`id`, `user_id`, `category_id`, `title`, `description`, `image_url`, `prep_time`, `difficulty`, `created_at`) VALUES
    (1,  1, 1, 'Snelle Shakshuka',
        'Eieren gepocheerd in een pittige tomatensaus. Een pan, tien minuten snijden en klaar.',
        'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=60', 20, 'makkelijk', '2026-10-01 10:00:00'),
    (2,  1, 1, 'Avocado Toast met Spiegelei',
        'Krokant brood, romige avocado en een eitje erop. Het ultieme studentenontbijt.',
        'https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?auto=format&fit=crop&w=800&q=60', 10, 'makkelijk', '2026-10-02 10:00:00'),
    (3,  1, 2, 'Loaded Tosti',
        'De tosti die je opvrolijkt: gesmolten kaas, tomaat en een krokant korstje.',
        'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=60', 10, 'makkelijk', '2026-10-03 10:00:00'),
    (4,  2, 2, 'Poke Bowl met Tonijn',
        'Frisse rijstkom met tonijn, avocado en courgette. Snel, gezond en Instagram-proof.',
        'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=800&q=60', 25, 'gemiddeld', '2026-10-05 10:00:00'),
    (5,  1, 4, 'Rode Linzensoep',
        'Warmende soep met linzen en tomaat. Maakt een grote pan voor weinig geld.',
        'https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&w=800&q=60', 30, 'makkelijk', '2026-10-07 10:00:00'),
    (6,  1, 5, 'Pasta Pesto met Kip',
        'Pasta met pesto en kipfilet. Klaar voordat je favoriete serie begint.',
        'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?auto=format&fit=crop&w=800&q=60', 20, 'makkelijk', '2026-10-09 10:00:00'),
    (7,  2, 5, 'Butter Chicken met Rijst',
        'Romige Indiase curry met kip. Iets meer werk, maar het hele huis ruikt geweldig.',
        'https://images.unsplash.com/photo-1565557623262-b51c2513a641?auto=format&fit=crop&w=800&q=60', 40, 'gemiddeld', '2026-10-12 10:00:00'),
    (8,  1, 3, 'Buddha Bowl met Quinoa',
        'Quinoa, geroosterde courgette en avocado in één kom. Maaltijd voor de hele week.',
        'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=800&q=60', 15, 'makkelijk', '2026-10-15 10:00:00'),
    (9,  1, 6, 'Choco Mug Cake',
        'Luchtige chocoladecake uit de magnetron. In 5 minuten klaar, in 2 minuten op.',
        'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=800&q=60', 5, 'makkelijk', '2026-10-18 10:00:00'),
    (10, 2, 6, 'Appelcrumble',
        'Warme appelcrumble met een krokant laagje. Ideaal om te delen na het eten.',
        'https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=800&q=60', 35, 'gemiddeld', '2026-10-21 10:00:00');

-- Ingrediënten per recept (n:n-koppeling met hoeveelheid) -----
INSERT IGNORE INTO `recipe_ingredients` (`recipe_id`, `ingredient_id`, `amount`) VALUES
    -- 1. Snelle Shakshuka
    (1, 1, '3 stuks'),     (1, 2, '400 g'),      (1, 9, '1 stuk'),     (1, 10, '2 teentjes'), (1, 11, '2 el'),
    -- 2. Avocado Toast met Spiegelei
    (2, 3, '2 sneetjes'),  (2, 4, '1 stuk'),     (2, 1, '2 stuks'),
    -- 3. Loaded Tosti
    (3, 3, '2 sneetjes'),  (3, 5, '4 plakken'),  (3, 2, '1 stuk'),
    -- 4. Poke Bowl met Tonijn
    (4, 8, '200 g'),       (4, 19, '1 blik'),    (4, 4, '1 stuk'),     (4, 13, '1 stuk'),
    -- 5. Rode Linzensoep
    (5, 12, '250 g'),      (5, 2, '400 g'),      (5, 9, '1 stuk'),     (5, 10, '3 teentjes'), (5, 11, '2 el'),
    -- 6. Pasta Pesto met Kip
    (6, 6, '250 g'),       (6, 18, '3 el'),      (6, 7, '1 stuk'),     (6, 10, '2 teentjes'),
    -- 7. Butter Chicken met Rijst
    (7, 7, '400 g'),       (7, 8, '250 g'),      (7, 2, '400 g'),      (7, 9, '2 stuks'),
    -- 8. Buddha Bowl met Quinoa
    (8, 17, '150 g'),      (8, 13, '1 stuk'),    (8, 4, '1 stuk'),     (8, 2, '2 stuks'),
    -- 9. Choco Mug Cake
    (9, 14, '30 g'),       (9, 15, '4 el'),      (9, 16, '3 el'),      (9, 22, '2 el'),
    -- 10. Appelcrumble
    (10, 21, '4 stuks'),   (10, 15, '150 g'),    (10, 22, '80 g');



