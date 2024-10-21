<?php
session_start();

// Проверка прав администратора
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Получаем информацию о книге для редактирования
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $book = $stmt->fetch();
}

// Обработка формы редактирования книги
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Обновляем информацию о книге
    $stmt = $pdo->prepare("UPDATE books SET title = :title, author = :author, category = :category, price = :price, status = :status WHERE id = :id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();

    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Редактировать книгу</title>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mt-5">
    <h1>Редактировать книгу</h1>
    <form method="POST">
        <div class="form-group">
            <label for="title">Название:</label>
            <input type="text" name="title" class="form-control" value="<?= $book['title'] ?>" required>
        </div>
        <div class="form-group">
            <label for="author">Автор:</label>
            <input type="text" name="author" class="form-control" value="<?= $book['author'] ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Категория:</label>
            <input type="text" name="category" class="form-control" value="<?= $book['category'] ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Цена:</label>
            <input type="number" name="price" class="form-control" value="<?= $book['price'] ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Статус:</label>
            <select name="status" class="form-control">
                <option value="available" <?= $book['status'] === 'available' ? 'selected' : '' ?>>Доступна</option>
                <option value="unavailable" <?= $book['status'] === 'unavailable' ? 'selected' : '' ?>>Недоступна</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Сохранить изменения</button>
    </form>
</div>
</body>
</html>
