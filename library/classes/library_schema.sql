CREATE DATABASE IF NOT EXISTS library;
USE library;


CREATE TABLE IF NOT EXISTS book (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(50),
    publication_year INT(4),
    publisher VARCHAR(255),
    copies INT(11) NOT NULL
);
