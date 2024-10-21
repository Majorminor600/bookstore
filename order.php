<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $order_type = $_POST['order_type']; // Получаем тип заказа из формы

    // Проверяем, что тип заказа допустим
    if (!in_array($order_type, ['purchase', 'rent'])) {
        die("Недопустимый тип заказа.");
    }

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, book_id, type, start_date, end_date) VALUES (:user_id, :book_id, :type, NOW(), DATE_ADD(NOW(), INTERVAL 2 WEEK))");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':book_id', $book_id);
    $stmt->bindParam(':type', $order_type);
    
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Ошибка: " . $e->getMessage();
        exit;
    }

    header("Location: profile.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
