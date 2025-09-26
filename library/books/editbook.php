<?php
include '../classes/database.php';
$error = [];
$isValid = true;
$pdo = null;

try {
    $db = new Library();
    $pdo = $db->connect();
} catch (PDOException $e) {
    echo "<p style='color:red;'>Database connection error: " . htmlspecialchars($e->getMessage()) . "</p>";
    $isValid = false;
}  
require_once "../classes/book.php";
$bookobj = new BOOK();

$book = [];
$error = [];

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {   
    $existingBook = $bookobj->getBookById($id);
    if ($existingBook) {
        $book = $existingBook;
    } else {
        echo "<p style='color:red;'>Book not found.</p>";
        exit();
    }
} else {
    echo "<p style='color:red;'>Invalid book ID.</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $isValid) {
    $book = [
        "title" => $_POST["title"] ?? '',
        "author" => $_POST["author"] ?? '',
        "genre" => $_POST["genre"] ?? '',
        "publication_year" => $_POST["publication_year"] ?? '',
        "publisher" => $_POST["publisher"] ?? '',
        "copies" => $_POST["copies"] ?? ''
    ];

    if (empty($book["title"])) {
        $error["title"] = "Title is required";
        $isValid = false;
    }
    if (empty($book["author"])) {
        $error["author"] = "Author is required";
        $isValid = false;
    }
    $allowed_genres = ["history", "science", "fiction"];
    if (empty($book["genre"])) {
        $error["genre"] = "Genre is required";
        $isValid = false;
    } else if (!in_array(strtolower($book["genre"]), $allowed_genres)) {
        $error["genre"] = "Invalid genre selected";
        $isValid = false;
    }
    if (empty($book["publication_year"])) {
        $error["publication_year"] = "Publication Year is required";
        $isValid = false;
    } else if (!filter_var($book["publication_year"], FILTER_VALIDATE_INT)) {
        $error["publication_year"] = "Invalid publication year";
        $isValid = false;
    } else if ($book["publication_year"] < 0) {
        $error["publication_year"] = "Publication year cannot be negative";
        $isValid = false;
    } else if ($book["publication_year"] > date("Y")) {
        $error["publication_year"] = "Publication year cannot be in the future";
        $isValid = false;
    }
    if (empty($book["publisher"])) {
        $error["publisher"] = "Publisher is required";
        $isValid = false;
    }
    if (empty($book["copies"])) {
        $error["copies"] = "Copies is required";
        $isValid = false;
    } else if (!filter_var($book["copies"], FILTER_VALIDATE_INT) || $book["copies"] < 1) {
        $error["copies"] = "Copies must be a positive integer";
        $isValid = false;
    }

    if (empty(array_filter($error))) {
        if ($book["title"] !== $existingBook["title"] && $bookobj->isBookTitleExist($book["title"])) {
            $error["title"] = "A book with this title already exists";
        } else {
            $bookobj->title = $book["title"];
            $bookobj->author = $book["author"];
            $bookobj->genre = strtolower($book["genre"]);
            $bookobj->publication_year = $book["publication_year"];
            $bookobj->publisher = $book["publisher"];
            $bookobj->copies = $book["copies"];

            try {
                if ($bookobj->updateBook($id)) {
                    header("Location: viewbook.php");
                    exit();
                } else {
                    echo "<p style='color:red;'>Error updating book.</p>";
                }
            } catch (PDOException $e) {
                echo "<p style='color:red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="addbookstyle.css">
    <style>
        p.error-message {
            color: red;
            text-align: left;
            margin-top: 5px;
        }
        label span {
            color: red;
        }
    </style>
</head>
<body>
        <h2>EDIT BOOK</h2>
        <form action="" method="post">
        <div class="field">
            <label for="title">Title:<span>*</span></label>
            <input type="text" id="title" name="title" value="<?= $book['title'] ?? '' ?>">
            <span class="error-message"><?= $error['title'] ?? '' ?></span>
        </div>
        <div class="field">
            <label for="author">Author:<span>*</span></label>
            <input type="text" id="author" name="author" value="<?= $book['author'] ?? '' ?>">
            <span class="error-message"><?= $error['author'] ?? '' ?></span>
        </div>
        <div class="field">
            <label for="genre">Genre:<span>*</span></label>
            <select id="genre" name="genre">
                <option value="">--Select Genre--</option>
                <option value="history" <?= (isset($book['genre']) && $book['genre'] === 'history') ? 'selected' : '' ?>>History</option>
                <option value="science" <?= (isset($book['genre']) && $book['genre'] === 'science') ? 'selected' : '' ?>>Science</option>
                <option value="fiction" <?= (isset($book['genre']) && $book['genre'] === 'fiction') ? 'selected' : '' ?>>Fiction</option>
            </select>
            <span class="error-message"><?= $error['genre'] ?? '' ?></span>
        </div>
        <div class="field">
            <label for="publication_year">Publication Year:<span>*</span></label>
            <input type="number" id="publication_year" name="publication_year" value="<?= $book['publication_year'] ?? '' ?>">
        </div>
        <p class="error-message"><?= $error['publication_year'] ?? '' ?></p>
        <div class="field">
            <label for="publisher">Publisher:<span>*</span></label>
            <input type="text" id="publisher" name="publisher" value="<?= $book['publisher'] ?? '' ?>">
            <span class="error-message"><?= $error['publisher'] ?? '' ?></span>
        </div>
        <div class="field">
            <label for="copies">Copies:<span>*</span></label>
            <input type="number" id="copies" name="copies" value="<?= $book['copies'] ?? '' ?>">
            <span class="error-message"><?= $error['copies'] ?? '' ?></span>
        </div>
        <button type="submit">Update Book</button>
         </form>
</body>
</html>
