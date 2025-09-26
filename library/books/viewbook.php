<?php
require_once '../classes/book.php';
$bookObj = new BOOK();

$title = $genre = '';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $title = isset($_GET['title']) ? trim(htmlspecialchars($_GET['title'])) : '';
    $genre = isset($_GET['genre']) ? trim(htmlspecialchars($_GET['genre'])) : '';

    if (isset($_GET['delete']) && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($bookObj->isbookExist($id)) {
            if ($bookObj->deleteBook($id)) {
                header("Location: viewbook.php");
                exit();
            } else {
                echo "<p class='error-message'>Error deleting book.</p>";
            }
        } else {
            echo "<p class='error-message'>Book not found.</p>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link rel="stylesheet" href="viewbookstyle.css">
    <style>
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
  <h2>BOOKS</h2>
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
        <button><a href="addbook.php" class="add-book-btn">Add New Book</a></button>
        <br>   
        <table border=1>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Publication Year</th>
                <th>Publisher</th>
                <th>Copies</th>
                <th>Actions</th>
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
                <td><a href="editbook.php?id=<?= $book['id'] ?>">Edit</a> | <a href="?delete=1&id=<?= $book['id'] ?>" onclick="return confirm('Are you sure you want to delete \'<?= $book['title'] ?>\'?')">Delete</a></td>
            </tr>
            <?php } ?>
        </table>
</body>
</html>
