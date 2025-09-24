<?php
require_once '../classes/book.php';
$bookObj = new BOOK();

$title = $genre = '';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $title = isset($_GET['title']) ? trim(htmlspecialchars($_GET['title'])) : '';
    $genre = isset($_GET['genre']) ? trim(htmlspecialchars($_GET['genre'])) : '';

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link rel="stylesheet" href="">
    <style>
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
  <h2>Search Books</h2>
   <form action="" method="get">
        <label for="title">Search Title:</label>
         <input type="text" id="title" name="title" value="<?= $title ?>">
        <label for="genre">Filter by Genre:</label>
            <select id="genre" name="genre">
                <option value="">--Select Genre--</option>
                <option value="history" <?= ($genre === 'history') ? 'selected' : '' ?>>History</option>
                <option value="science" <?= ($genre === 'science') ? 'selected' : '' ?>>Science</option>
                <option value="fiction" <?= ($genre === 'fiction') ? 'selected' : '' ?>>Fiction</option>
            </select>
            <button type="submit">Search</button>
    </form>
        <h2>View Books</h2>
        <button><a href="addbook.php" class="add-book-btn">Add New Book</a></button>
        <table border=1>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Publication Year</th>
                <th>Publisher</th>
                <th>Copies</th>
            </tr>
            <?php
            $no = 1;
            foreach ($bookObj->viewBooks($title, $genre) as $book){
                ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $book['title']; ?></td>
                <td><?= $book['author']; ?></td>
                <td><?= $book['genre']; ?></td>
                <td><?= $book['publication_year']; ?></td>
                <td><?= $book['publisher']; ?></td>
                <td><?= $book['copies']; ?></td>
            </tr>
            <?php } ?>
        </table>
</body>
</html>
