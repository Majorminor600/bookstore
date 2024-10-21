<?php
session_start();

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Получаем заказы пользователя
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT books.title, orders.type, orders.start_date, orders.end_date 
                       FROM orders
                       JOIN books ON orders.book_id = books.id
                       WHERE orders.user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Мой профиль</title>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mt-5">
    <h1>Мои заказы</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Название книги</th>
                <th>Тип</th>
                <th>Дата начала</th>
                <th>Дата окончания</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['title'] ?></td>
                <td><?= $order['type'] === 'rent' ? 'Аренда' : 'Покупка' ?></td>
                <td><?= $order['start_date'] ?></td>
                <td><?= $order['end_date'] ?? 'Не применимо' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
