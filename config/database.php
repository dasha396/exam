<?php
$pdo = new PDO("mysql:host=localhost;dbname=exam_db;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>