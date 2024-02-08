-- Last Update: 2024/02/08 21:00

CREATE TABLE Role(
   id VARCHAR(36),
   name VARCHAR(50) NOT NULL,
   permission_rank INT NOT NULL,
   PRIMARY KEY(id)
);

CREATE TABLE Member_(
   id VARCHAR(36),
   creation_date DATE NOT NULL,
   lastname VARCHAR(50) NOT NULL,
   firstname VARCHAR(50) NOT NULL,
   birth_date DATE NOT NULL,
   email VARCHAR(50) NOT NULL,
   adress VARCHAR(80) NOT NULL,
   phone_number VARCHAR(10) NOT NULL,
   photo_link VARCHAR(255),
   password VARCHAR(255) NOT NULL,
   id_1 VARCHAR(36) NOT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY(id_1) REFERENCES Role(id)
);

CREATE TABLE Book(
   id VARCHAR(36),
   title VARCHAR(100) NOT NULL,
   summary TEXT,
   release_date DATE NOT NULL,
   language_ VARCHAR(50) NOT NULL,
   cover_link VARCHAR(150) NOT NULL,
   page_number INT NOT NULL,
   PRIMARY KEY(id)
);

CREATE TABLE Author(
   id VARCHAR(36),
   lastname VARCHAR(50) NOT NULL,
   firstname VARCHAR(50) NOT NULL,
   nationality VARCHAR(50) NOT NULL,
   photo_link VARCHAR(150),
   description TEXT NOT NULL,
   birth_date DATE,
   death_date DATE,
   PRIMARY KEY(id)
);

CREATE TABLE Category(
   id VARCHAR(36),
   name VARCHAR(50) NOT NULL,
   description VARCHAR(255) NOT NULL,
   PRIMARY KEY(id)
);

CREATE TABLE Suggestion(
   id VARCHAR(36),
   title VARCHAR(50) NOT NULL,
   release_date DATE,
   description TEXT,
   details TEXT,
   authors VARCHAR(50) NOT NULL,
   editor VARCHAR(50) NOT NULL,
   PRIMARY KEY(id)
);

CREATE TABLE Loan(
   id VARCHAR(36),
   loan_date DATE NOT NULL,
   return_date VARCHAR(50),
   id_1 VARCHAR(36) NOT NULL,
   id_2 VARCHAR(36) NOT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY(id_1) REFERENCES Book(id),
   FOREIGN KEY(id_2) REFERENCES Member_(id)
);

CREATE TABLE Reservation(
   id VARCHAR(36),
   date_resa DATE NOT NULL,
   id_1 VARCHAR(36) NOT NULL,
   id_2 VARCHAR(36) NOT NULL,
   PRIMARY KEY(id),
   UNIQUE(id_1),
   FOREIGN KEY(id_1) REFERENCES Book(id),
   FOREIGN KEY(id_2) REFERENCES Member_(id)
);

CREATE TABLE book_author(
   id VARCHAR(36),
   id_1 VARCHAR(36),
   PRIMARY KEY(id, id_1),
   FOREIGN KEY(id) REFERENCES Book(id),
   FOREIGN KEY(id_1) REFERENCES Author(id)
);

CREATE TABLE book_category(
   id VARCHAR(36),
   id_1 VARCHAR(36),
   PRIMARY KEY(id, id_1),
   FOREIGN KEY(id) REFERENCES Book(id),
   FOREIGN KEY(id_1) REFERENCES Category(id)
);

CREATE TABLE make(
   id VARCHAR(36),
   id_1 VARCHAR(36),
   PRIMARY KEY(id, id_1),
   FOREIGN KEY(id) REFERENCES Member_(id),
   FOREIGN KEY(id_1) REFERENCES Suggestion(id)
);
