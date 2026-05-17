<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = false;
if ($is_logged_in) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $is_admin = ($user && $user['role'] === 'admin');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="images/logo.png" alt="Логотип" onerror="this.src='https://via.placeholder.com/50'">
            <span>Мой Портал</span>
        </div>
        <div class="nav">
            <a href="index.php">Главная</a>
            <?php if ($is_logged_in): ?>
                <a href="dashboard.php">Кабинет</a>
                <a href="add_application.php">Заявка</a>
                <?php if ($is_admin): ?>
                    <a href="admin.php">Админка</a>
                <?php endif; ?>
                <a href="logout.php">Выход</a>
            <?php else: ?>
                <a href="login.php">Вход</a>
                <a href="register.php">Регистрация</a>
            <?php endif; ?>
        </div>
    </div>