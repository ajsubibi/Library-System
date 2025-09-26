<?php

require_once "database.php";

class BOOK extends Library {
    public $id;
    public $title;
    public $author;
    public $genre;
    public $publication_year;
    public $publisher;
    public $copies;

    public function addBook() {
        $sql = "INSERT INTO book (title, author, genre, publication_year, publisher, copies) VALUES (:title, :author, :genre, :publication_year, :publisher, :copies)";

        $query = $this->connect()->prepare($sql);

        $query->bindParam(':title', $this->title);
        $query->bindParam(':author', $this->author);
        $query->bindParam(':genre', $this->genre);
        $query->bindParam(':publication_year', $this->publication_year);
        $query->bindParam(':publisher', $this->publisher);
        $query->bindParam(':copies', $this->copies);
        return $query->execute();
    }
   public function viewBooks($title = '', $genre = '') {
        $sql = "SELECT * FROM book WHERE title like CONCAT('%', :title, '%') and genre like CONCAT('%',:genre,'%')
        ORDER BY id ASC";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':title', $title);
        $query->bindParam(':genre', $genre);

        if ($query->execute()) {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            return null;
        }
    }

    public function isbookExist($id) {
        $sql = "SELECT COUNT(*) FROM book WHERE id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        $record = null;
        if ($query->execute()) {
            $record = $query->fetch();
        }
        if ($record && $record[0] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isBookTitleExist($title) {
        $sql = "SELECT COUNT(*) FROM book WHERE title = :title";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':title', $title);
        $record = null;
        if ($query->execute()) {
            $record = $query->fetch();
        }
        if ($record && $record[0] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteBook($id) {
        $sql = "DELETE FROM book WHERE id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        return $query->execute();
    }

    public function getBookById($id) {
        $sql = "SELECT * FROM book WHERE id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        if ($query->execute()) {
            return $query->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    public function updateBook($id) {
        $sql = "UPDATE book SET title = :title, author = :author, genre = :genre, publication_year = :publication_year, publisher = :publisher, copies = :copies WHERE id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->bindParam(':title', $this->title);
        $query->bindParam(':author', $this->author);
        $query->bindParam(':genre', $this->genre);
        $query->bindParam(':publication_year', $this->publication_year);
        $query->bindParam(':publisher', $this->publisher);
        $query->bindParam(':copies', $this->copies);
        return $query->execute();
    }

}
