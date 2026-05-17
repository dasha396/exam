<?php
require_once 'config/database.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$stmt = $pdo->query("SELECT id, name FROM services ORDER BY name");
$services = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    
    if (empty($service_id) || empty($start_date) || empty($payment_method)) {
        $error = 'Заполните все поля';
    } else {
        $sql = "INSERT INTO applications (user_id, service_id, start_date, payment_method, status) 
                VALUES (?, ?, ?, ?, 'Новая')";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$user_id, $service_id, $start_date, $payment_method])) {
            $success = 'Заявка отправлена! <a href="dashboard.php">Вернуться</a>';
        } else {
            $error = 'Ошибка';
        }
    }
}
?>

<div class="container">
    <h1>Новая заявка</h1>
    
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php else: ?>
        <form method="POST">
            <label>Выберите услугу:</label>
            <select name="service_id" required>
                <option value="">-- Выберите --</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
                <?php endforeach; ?>
            </select>
            
            <label>Дата начала:</label>
            <input type="date" name="start_date" required>
            
            <label>Оплата:</label>
            <select name="payment_method" required>
                <option value="cash">Наличные</option>
                <option value="transfer">Перевод</option>
            </select>
            
            <button type="submit">Отправить</button>
        </form>
    <?php endif; ?>
    <p><a href="dashboard.php">← Назад</a></p>
</div>

<?php include 'footer.php'; ?>