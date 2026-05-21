<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admi.php');
    exit;
}
require_once 'config.php';

// Traitement de la décision
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['eleve_id'])) {
    $eleve_id = intval($_POST['eleve_id']);
    $action = $_POST['action'];
    $nouveau_statut = ($action === 'accepter') ? 'admis' : 'non_admis';
    
    $stmt = $pdo->prepare("UPDATE eleves SET statut_inscription = ? WHERE id = ?");
    $stmt->execute([$nouveau_statut, $eleve_id]);
    $message = "Décision enregistrée.";
}

// Récupérer les élèves en attente
$stmt = $pdo->query("SELECT * FROM eleves WHERE statut_inscription = 'en_attente' ORDER BY date_inscription DESC");
$attentes = $stmt->fetchAll();

// Récupérer tous les élèves pour affichage (optionnel)
$stmt = $pdo->query("SELECT * FROM eleves ORDER BY date_inscription DESC");
$tous = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des inscriptions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<div class="container mt-5">
    <h2>Gestion des demandes d'inscription</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Retour au tableau de bord</a>
    
    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    
    <h3>Nouvelles demandes (en attente)</h3>
    <?php if (count($attentes) == 0): ?>
        <p>Aucune demande en attente.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Nom complet</th><th>Email</th><th>Classe</th><th>Option</th><th>Date inscription</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php foreach ($attentes as $e): ?>
            <tr>
                <td><?= $e['id'] ?></td>
                <td><?= htmlspecialchars($e['nom'] . ' ' . $e['prenom'] . ' ' . $e['postnom']) ?></td>
                <td><?= htmlspecialchars($e['email']) ?></td>
                <td><?= htmlspecialchars($e['classe_actuelle']) ?></td>
                <td><?= htmlspecialchars($e['option_souhaitee']) ?></td>
                <td><?= $e['date_inscription'] ?></td>
                <td>
                    <form method="post" style="display:inline-block">
                        <input type="hidden" name="eleve_id" value="<?= $e['id'] ?>">
                        <button type="submit" name="action" value="accepter" class="btn btn-success btn-sm">Accepter</button>
                        <button type="submit" name="action" value="refuser" class="btn btn-danger btn-sm">Refuser</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <h3>Tous les élèves inscrits</h3>
    <table class="table table-striped">
        <thead>
            <tr><th>ID</th><th>Nom</th><th>Email</th><th>Classe</th><th>Statut</th></tr>
        </thead>
        <tbody>
        <?php foreach ($tous as $e): ?>
        <tr>
            <td><?= $e['id'] ?></td>
            <td><?= htmlspecialchars($e['nom'] . ' ' . $e['prenom']) ?></td>
            <td><?= htmlspecialchars($e['email']) ?></td>
            <td><?= htmlspecialchars($e['classe_actuelle']) ?></td>
            <td><?= $e['statut_inscription'] ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>