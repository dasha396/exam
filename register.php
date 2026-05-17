<?php
require_once 'config/database.php';
include 'header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    if (!preg_match('/^[a-zA-Z0-9]{6,}$/', $login)) {
        $error = 'Логин: минимум 6 символов, латиница и цифры';
    } elseif (strlen($password) < 8) {
        $error = 'Пароль: минимум 8 символов';
    } elseif (!preg_match('/^[а-яА-ЯёЁ\s]+$/u', $full_name)) {
        $error = 'ФИО: только кириллица и пробелы';
    } elseif (!preg_match('/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $phone)) {
        $error = 'Телефон: формат 8(XXX)XXX-XX-XX';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный email';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            $error = 'Такой логин уже существует';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (login, password, full_name, phone, email, role) VALUES (?, ?, ?, ?, ?, 'user')";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$login, $hashed, $full_name, $phone, $email])) {
                $success = 'Регистрация успешна! <a href="login.php">Войти</a>';
            } else {
                $error = 'Ошибка при регистрации';
            }
        }
    }
}
?>

<div class="container">
    <h1>Регистрация</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <label>Логин (лат.+цифры, ≥6):</label>
        <input type="text" name="login" required>
        
        <label>Пароль (≥8):</label>
        <input type="password" name="password" required>
        
        <label>ФИО (кириллица):</label>
        <input type="text" name="full_name" required>
        
        <label>Телефон (8(XXX)XXX-XX-XX):</label>
        <input type="text" name="phone" placeholder="8(999)123-45-67" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">Уже есть аккаунт? <a href="login.php">Войти</a></p>
</div>

<?php include 'footer.php'; ?>