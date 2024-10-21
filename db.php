<?php
$host = 'localhost';  // Хост
$db = 'bookstore';    // Имя базы данных
$user = 'root';       // Имя пользователя
$pass = '';           // Пароль

// Подключение к базе данных через PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
