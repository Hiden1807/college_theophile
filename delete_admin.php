<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    header('Location: dashboard.php');
    exit;
}
require_once 'config.php';

$id = intval($_GET['id'] ?? 0);
if ($id > 0 && $id != $_SESSION['admin_id']) {
    $stmt = $pdo->prepare("DELETE FROM administrateurs WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: admin_manage.php');
exit;
?>