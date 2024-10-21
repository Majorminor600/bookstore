<?php
session_start();
include 'db.php';

// Фильтры
$filterCategory = isset($_GET['category']) ? $_GET['category'] : '';
$filterAuthor = isset($_GET['author']) ? $_GET['author'] : '';
$filterYear = isset($_GET['year']) ? $_GET['year'] : '';

// Получаем список категорий, авторов и годов для фильтров
$categories = $pdo->query("SELECT DISTINCT category FROM books")->fetchAll(PDO::FETCH_COLUMN);
$authors = $pdo->query("SELECT DISTINCT author FROM books")->fetchAll(PDO::FETCH_COLUMN);
$years = $pdo->query("SELECT DISTINCT year_published FROM books ORDER BY year_published DESC")->fetchAll(PDO::FETCH_COLUMN);

// Получаем список книг из базы данных с учетом фильтров
$query = "SELECT * FROM books WHERE status = 'available'";
if ($filterCategory) {
    $query .= " AND category = :category";
}
if ($filterAuthor) {
    $query .= " AND author = :author";
}
if ($filterYear) {
    $query .= " AND year_published = :year";
}

$stmt = $pdo->prepare($query);

if ($filterCategory) {
    $stmt->bindParam(':category', $filterCategory);
}
if ($filterAuthor) {
    $stmt->bindParam(':author', $filterAuthor);
}
if ($filterYear) {
    $stmt->bindParam(':year', $filterYear);
}

$stmt->execute();
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Книжный магазин</title>
    <style>
        .card-img-top {
            max-height: 200px; /* Ограничение высоты изображений */
            object-fit: cover; /* Обеспечение корректного отображения изображений */
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mt-5">
    <h1>Добро пожаловать в книжный магазин!</h1>

    <form method="GET" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="category">Категория:</label>
                <select name="category" class="form-control" id="category">
                    <option value="">Все</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category ?>" <?= $filterCategory === $category ? 'selected' : '' ?>><?= $category ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="author">Автор:</label>
                <select name="author" class="form-control" id="author">
                    <option value="">Все</option>
                    <?php foreach ($authors as $author): ?>
                        <option value="<?= $author ?>" <?= $filterAuthor === $author ? 'selected' : '' ?>><?= $author ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="year">Год издания:</label>
                <select name="year" class="form-control" id="year">
                    <option value="">Все</option>
                    <?php foreach ($years as $year): ?>
                        <option value="<?= $year ?>" <?= $filterYear === $year ? 'selected' : '' ?>><?= $year ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Фильтровать</button>
    </form>

    <h2>Доступные книги</h2>
    <div class="row">
        <?php foreach ($books as $book): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="<?= $book['cover_image'] ?>" class="card-img-top" alt="<?= $book['title'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $book['title'] ?></h5>
                    <p class="card-text">Автор: <?= $book['author'] ?></p>
                    <p class="card-text">Цена: <?= $book['price'] ?> руб.</p>
                    <a href="book.php?id=<?= $book['id'] ?>" class="btn btn-primary">Посмотреть</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
