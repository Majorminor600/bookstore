<?php
session_start();

// Проверка прав администратора
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Получаем список книг из базы данных
$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Административная панель</title>
</head>
<body>

<!-- Включаем шапку -->
<?php include 'header.php'; ?>

<div class="container mt-5">
    <h1>Управление книгами</h1>
    <a href="add_book.php" class="btn btn-success mb-4">Добавить книгу</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Название</th>
                <th>Автор</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?= $book['title'] ?></td>
                <td><?= $book['author'] ?></td>
                <td><?= $book['category'] ?></td>
                <td><?= $book['price'] ?> руб.</td>
                <td><?= $book['status'] === 'available' ? 'Доступна' : 'Недоступна' ?></td>
                <td>
                    <a href="edit_book.php?id=<?= $book['id'] ?>" class="btn btn-warning btn-sm">Редактировать</a>
                    <a href="delete_book.php?id=<?= $book['id'] ?>" class="btn btn-danger btn-sm">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
