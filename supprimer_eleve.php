<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admi.php');
    exit;
}
require_once 'config.php';

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM eleves WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: admin_eleves.php');
exit;
?>