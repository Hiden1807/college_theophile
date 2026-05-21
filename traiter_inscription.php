<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admi.php');
    exit;
}
require_once 'config.php';

$id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if ($id && in_array($action, ['admis', 'non_admis'])) {
    $statut = ($action === 'admis') ? 'admis' : 'non_admis';
    $stmt = $pdo->prepare("UPDATE eleves SET statut_inscription = ? WHERE id = ?");
    $stmt->execute([$statut, $id]);
}
header('Location: dashboard.php');
exit;
?>