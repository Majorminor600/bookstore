<?php
session_start();
include 'db.php';

// Проверяем, есть ли идентификатор книги в URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

// Получаем информацию о книге из базы данных
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$book = $stmt->fetch();

if (!$book) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title><?= $book['title'] ?></title>
    <style>
        .book-image {
            max-height: 300px; /* Установка максимальной высоты */
            max-width: 100%;    /* Установка максимальной ширины */
            object-fit: cover;  /* Обеспечение корректного отображения изображений */
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mt-5">
    <h1><?= $book['title'] ?></h1>
    <div class="row">
        <div class="col-md-6">
            <img src="<?= $book['cover_image'] ?>" class="img-fluid book-image" alt="<?= $book['title'] ?>">
        </div>
        <div class="col-md-6">
            <h5>Автор: <?= $book['author'] ?></h5>
            <h5>Категория: <?= $book['category'] ?></h5>
            <h5>Год издания: <?= $book['year_published'] ?></h5>
            <h5>Цена: <?= $book['price'] ?> руб.</h5>
            <p><?= $book['description'] ?></p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="order.php" method="POST">
					<input type="hidden" name="book_id" value="<?= $book['id'] ?>">
					<label for="order_type">Тип заказа:</label>
					<select name="order_type" class="form-control" id="order_type">
						<option value="purchase">Купить</option>
						<option value="rent">Арендовать</option>
					</select>
					<button type="submit" class="btn btn-primary">Подтвердить заказ</button>
				</form>
            <?php else: ?>
                <p>Чтобы купить или арендовать, <a href="login.php">войдите</a> или <a href="register.php">зарегистрируйтесь</a>.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
