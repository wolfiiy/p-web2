drop database db_passion_lecture;
create database if not exists db_passion_lecture
CHARACTER SET utf8
COLLATE utf8_general_ci;

use db_passion_lecture;
SET NAMES 'utf8';


CREATE TABLE if not exists t_category(
   category_id INT AUTO_INCREMENT,
   name VARCHAR(50) NOT NULL,
   PRIMARY KEY(category_id),
   UNIQUE(name)
)CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE if not exists t_author(
   author_id INT AUTO_INCREMENT,
   first_name VARCHAR(128) NOT NULL,
   last_name VARCHAR(128) NOT NULL,
   PRIMARY KEY(author_id)
)CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE if not exists t_publisher(
   publisher_id INT AUTO_INCREMENT,
   name VARCHAR(50) NOT NULL,
   PRIMARY KEY(publisher_id)
)CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE if not exists t_user(
   user_id INT AUTO_INCREMENT,
   username VARCHAR(50) NOT NULL,
   pass VARCHAR(50) NOT NULL,
   sign_up_date DATE NOT NULL,
   is_admin BOOLEAN NOT NULL,
   PRIMARY KEY(user_id)
)CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE if not exists t_book(
   book_id INT AUTO_INCREMENT,
   title VARCHAR(100) NOT NULL,
   excerpt VARCHAR(255) NOT NULL,
   summary TEXT NOT NULL,
   release_date DATE NOT NULL,
   cover_image VARCHAR(128) NOT NULL,
   number_of_pages SMALLINT NOT NULL,
   user_fk INT NOT NULL,
   category_fk INT NOT NULL,
   publisher_fk INT NOT NULL,
   author_fk INT NOT NULL,
   PRIMARY KEY(book_id),
   FOREIGN KEY(user_fk) REFERENCES t_user(user_id),
   FOREIGN KEY(category_fk) REFERENCES t_category(category_id),
   FOREIGN KEY(publisher_fk) REFERENCES t_publisher(publisher_id),
   FOREIGN KEY(author_fk) REFERENCES t_author(author_id)
)CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE if not exists review(
   book_fk INT,
   user_fk INT,
   grade TINYINT,
   PRIMARY KEY(book_fk, user_fk),
   FOREIGN KEY(book_fk) REFERENCES t_book(book_id),
   FOREIGN KEY(user_fk) REFERENCES t_user(user_id)
)CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Data
INSERT INTO t_category (name) VALUES 
("roman"), 
("manga"), 
("bande-dessinee"), 
("nouvelle"), 
("poesie");
-- Ajout de 6 éditeurs
INSERT INTO t_publisher (name) VALUES
("Gallimard"),
("Flammarion"),
("Grasset"),
("Albin Michel"),
("Folio"),
("Le Livre de Poche");

-- Ajout de 6 auteurs célèbres français
INSERT INTO t_author (first_name, last_name) VALUES
("Victor", "Hugo"),
("Émile", "Zola"),
("Marcel", "Proust"),
("Albert", "Camus"),
("Gustave", "Flaubert"),
("George", "Sand");

-- Ajout de 3 utilisateurs
INSERT INTO t_user (username, pass, sign_up_date, is_admin) VALUES
("booklover123", "123", "2022-01-15", false),
("admin_guy", "123", "2021-12-05", true),
("reading_queen", "123", "2023-03-22", false);

-- Ajout de 6 livres
INSERT INTO t_book (title, excerpt, summary, release_date, cover_image, number_of_pages, user_fk, category_fk, publisher_fk, author_fk) VALUES
-- Les Misérables par Victor Hugo, publié par Folio
("Les Misérables", "Un chef-d'oeuvre de la littérature française", "Un roman qui explore la nature humaine et la justice sociale.", "1862-04-03", "les_miserables.jpg", 1463, 1, 1, 5, 1),

-- Germinal par Émile Zola, publié par Le Livre de Poche
("Germinal", "Un portrait saisissant des luttes ouvrières", "Le récit poignant de la vie dans les mines du nord de la France.", "1885-03-25", "germinal.jpg", 592, 2, 1, 6, 2),

-- À la recherche du temps perdu par Marcel Proust, publié par Gallimard
("À la recherche du temps perdu", "Un voyage dans les souvenirs", "Une exploration complexe des souvenirs et des émotions humaines.", "1913-11-14", "recherche_du_temps_perdu.jpg", 4211, 3, 1, 1, 3),

-- L'Étranger par Albert Camus, publié par Gallimard
("L'Étranger", "Une réflexion sur l'absurde", "Une histoire captivante d'indifférence et de choix personnels.", "1942-06-19", "l_etranger.jpg", 123, 1, 1, 1, 4),

-- Madame Bovary par Gustave Flaubert, publié par Flammarion
("Madame Bovary", "Un roman d'amour et de tragédie", "L'histoire d'une femme prise au piège par ses rêves romantiques.", "1857-12-15", "madame_bovary.jpg", 362, 2, 1, 2, 5),

-- La Mare au Diable par George Sand, publié par Albin Michel
("La Mare au Diable", "Un roman pastoral", "Un conte touchant sur l'amour et la vie rurale.", "1846-03-02", "mare_au_diable.jpg", 184, 3, 1, 4, 6);
