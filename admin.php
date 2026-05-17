<?php
require_once 'config/database.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['new_status'], $_POST['application_id']]);
    $message = 'Статус изменён';
}

$applications = $pdo->query("
    SELECT a.*, u.login, s.name as service_name 
    FROM applications a
    JOIN users u ON a.user_id = u.id
    JOIN services s ON a.service_id = s.id
    ORDER BY a.created_at DESC
")->fetchAll();
?>

<div class="container">
    <h1>Админ-панель</h1>
    <p><a href="index.php">Главная</a> | <a href="logout.php">Выйти</a></p>
    
    <?php if ($message): ?>
        <p style="color: green;"><?= $message ?></p>
    <?php endif; ?>
    
    <h2>Все заявки</h2>
    
    <table>
        <thead>
            <tr><th>ID</th><th>Пользователь</th><th>Услуга</th><th>Дата</th><th>Статус</th><th>Действие</th></tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $app): ?>
            <form method="POST">
                <tr>
                    <td><?= $app['id'] ?><input type="hidden" name="application_id" value="<?= $app['id'] ?>"></td>
                    <td><?= htmlspecialchars($app['login']) ?></td>
                    <td><?= htmlspecialchars($app['service_name']) ?></td>
                    <td><?= $app['start_date'] ?></td>
                    <td><?= $app['status'] ?></td>
                    <td>
                        <select name="new_status">
                            <option value="Новая">Новая</option>
                            <option value="В работе">В работе</option>
                            <option value="Завершено">Завершено</option>
                        </select>
                        <button type="submit">Сменить</button>
                    </td>
                </tr>
            </form>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>