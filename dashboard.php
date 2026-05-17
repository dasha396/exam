<?php
require_once 'config/database.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT a.*, s.name as service_name 
    FROM applications a
    JOIN services s ON a.service_id = s.id
    WHERE a.user_id = ?
    ORDER BY a.created_at DESC
");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll();
?>

<div class="container">
    <h1>👋 Привет, <?= htmlspecialchars($_SESSION['login']) ?></h1>
    <p><a href="add_application.php">➕ Новая заявка</a> | <a href="logout.php">🚪 Выйти</a></p>
    
    <h2>Мои заявки</h2>
    
    <?php if (count($applications) > 0): ?>
        <table>
            <thead>
                <tr><th>Услуга</th><th>Дата</th><th>Оплата</th><th>Статус</th></tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?= htmlspecialchars($app['service_name']) ?></td>
                    <td><?= $app['start_date'] ?></td>
                    <td><?= $app['payment_method'] == 'cash' ? 'Наличные' : 'Перевод' ?></td>
                    <td>
                        <span class="status-<?= 
                            $app['status'] == 'Новая' ? 'new' : 
                            ($app['status'] == 'В работе' ? 'learning' : 'completed')
                        ?>"><?= $app['status'] ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>У вас нет заявок. <a href="add_application.php">Создать</a></p>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>