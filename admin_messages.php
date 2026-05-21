<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admi.php');
    exit;
}
require_once 'config.php';

// Marquer un message comme lu
if (isset($_GET['lu'])) {
    $id = intval($_GET['lu']);
    $pdo->prepare("UPDATE messages SET lu = 1 WHERE id = ?")->execute([$id]);
    header('Location: admin_messages.php');
    exit;
}

$messages = $pdo->query("SELECT * FROM messages ORDER BY date_envoi DESC")->fetchAll();
$nbNonLus = count(array_filter($messages, fn($m) => !$m['lu']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messages reçus - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <style>
        body { background: #f0f2f5; }
        .container { margin-top: 2rem; }
        .card-header { background: linear-gradient(90deg, #0a58ca, #0a3d62); }
        .table-responsive { border-radius: 1rem; overflow: hidden; }
        .badge-nonlu { background-color: #dc3545; font-size: 0.8rem; }
        .btn-action { border-radius: 2rem; padding: 0.25rem 0.8rem; }
        @media (max-width: 768px) {
            .table thead { display: none; }
            .table, .table tbody, .table tr, .table td { display: block; width: 100%; }
            .table tr { margin-bottom: 1rem; border: 1px solid #000000; border-radius: 0.5rem; padding: 0.5rem; background: white; }
            .table td { display: flex; justify-content: space-between; align-items: center; border: none; }
            .table td::before { content: attr(data-label); font-weight: bold; width: 40%; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card shadow">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Messages du formulaire de contact</h3>
            <div>
                <?php if ($nbNonLus > 0): ?>
                    <span class="badge bg-danger rounded-pill me-2"><?= $nbNonLus ?> non lus</span>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-light btn-sm">← Retour</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-dark">
                        <tr><th>Date</th><th>Nom</th><th>Email</th><th>Service</th><th>Message</th><th>Lu</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($messages as $m): ?>
                        <tr>
                            <td data-label="Date"><?= htmlspecialchars($m['date_envoi']) ?></td>
                            <td data-label="Nom"><?= htmlspecialchars($m['nom_complet']) ?></td>
                            <td data-label="Email"><?= htmlspecialchars($m['email']) ?></td>
                            <td data-label="Service"><?= htmlspecialchars($m['service']) ?></td>
                            <td data-label="Message"><?= nl2br(htmlspecialchars($m['message'])) ?></td>
                            <td data-label="Lu"><?= $m['lu'] ? 'Oui' : 'Non' ?></td>
                            <td data-label="Action">
                                <a href="mailto:<?= $m['email'] ?>?subject=Réponse à votre message" class="btn btn-sm btn-primary btn-action mb-1">Répondre</a>
                                <?php if (!$m['lu']): ?>
                                    <a href="?lu=<?= $m['id'] ?>" class="btn btn-sm btn-secondary btn-action">Marquer lu</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>