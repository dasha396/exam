<?php
require_once 'config/database.php';
include 'header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>

<div class="container">
    <h1>Вход</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Логин:</label>
        <input type="text" name="login" required>
        
        <label>Пароль:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Войти</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">Нет аккаунта? <a href="register.php">Регистрация</a></p>
</div>

<?php include 'footer.php'; ?>