<?php
session_start();

// Проверка прав администратора
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Вставляем новую книгу в базу данных
    $stmt = $pdo->prepare("INSERT INTO books (title, author, category, price, status) VALUES (:title, :author, :category, :price, :status)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':status', $status);
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
    <title>Добавить книгу</title>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mt-5">
    <h1>Добавить книгу</h1>
    <form method="POST">
        <div class="form-group">
            <label for="title">Название:</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="author">Автор:</label>
            <input type="text" name="author" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="category">Категория:</label>
            <input type="text" name="category" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Цена:</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="status">Статус:</label>
            <select name="status" class="form-control">
                <option value="available">Доступна</option>
                <option value="unavailable">Недоступна</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Добавить</button>
    </form>
</div>
</body>
</html>
