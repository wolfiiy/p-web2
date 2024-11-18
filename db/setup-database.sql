create database if not exists db_passion-lecture

CREATE TABLE if not exists t_category(
   category_id INT AUTO_INCREMENT,
   name VARCHAR(50) NOT NULL,
   PRIMARY KEY(category_id),
   UNIQUE(name)
);

CREATE TABLE if not exists t_author(
   author_id INT AUTO_INCREMENT,
   first_name VARCHAR(128) NOT NULL,
   last_name VARCHAR(128) NOT NULL,
   PRIMARY KEY(author_id)
);

CREATE TABLE if not exists t_publisher(
   publisher_id INT AUTO_INCREMENT,
   name VARCHAR(50) NOT NULL,
   PRIMARY KEY(publisher_id)
);

CREATE TABLE if not exists t_user(
   user_id INT AUTO_INCREMENT,
   username VARCHAR(50) NOT NULL,
   sign_up_date DATE NOT NULL,
   is_admin BOOLEAN NOT NULL,
   PRIMARY KEY(user_id)
);

CREATE TABLE if not exists t_book(
   book_id INT AUTO_INCREMENT,
   title VARCHAR(100) NOT NULL,
   excerpt VARCHAR(255) NOT NULL,
   summary TEXT NOT NULL,
   release_date DATE NOT NULL,
   cover_image VARCHAR(128) NOT NULL,
   number_of_pages SMALLINT NOT NULL,
   user_id INT NOT NULL,
   category_id INT NOT NULL,
   publisher_id INT NOT NULL,
   author_id INT NOT NULL,
   PRIMARY KEY(book_id),
   FOREIGN KEY(user_fk) REFERENCES t_user(user_id),
   FOREIGN KEY(category_fk) REFERENCES t_category(category_id),
   FOREIGN KEY(publisher_fk) REFERENCES t_publisher(publisher_id),
   FOREIGN KEY(author_fk) REFERENCES t_author(author_id)
);

CREATE TABLE if not exists review(
   book_id INT,
   user_id INT,
   grade TINYINT,
   PRIMARY KEY(book_id, user_id),
   FOREIGN KEY(book_fk) REFERENCES t_book(book_id),
   FOREIGN KEY(user_fk) REFERENCES t_user(user_id)
);
