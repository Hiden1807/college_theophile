<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admi.php');
    exit;
}
require_once 'config.php';

// --- Statistiques générales ---
$totalEleves = $pdo->query("SELECT COUNT(*) FROM eleves")->fetchColumn();
$messagesNonLus = $pdo->query("SELECT COUNT(*) FROM messages WHERE lu = 0")->fetchColumn();
$totalAdmins = $pdo->query("SELECT COUNT(*) FROM administrateurs")->fetchColumn();
$demandesAttente = $pdo->query("SELECT COUNT(*) FROM eleves WHERE statut_inscription = 'en_attente'")->fetchColumn();

// --- Effectifs par classe / option ---
$sql = "SELECT classe_actuelle, option_souhaitee, COUNT(*) as nb_eleves 
        FROM eleves 
        GROUP BY classe_actuelle, option_souhaitee 
        ORDER BY FIELD(classe_actuelle, '7 ème', '8 ème', '1 ère', '2 ème', '3 ème', '4 ème'), option_souhaitee";
$effectifs = $pdo->query($sql)->fetchAll();

$totauxClasse = [];
foreach ($effectifs as $e) {
    $classe = $e['classe_actuelle'];
    $totauxClasse[$classe] = ($totauxClasse[$classe] ?? 0) + $e['nb_eleves'];
}
$totalGeneral = array_sum($totauxClasse);

$infosClasse = [
    '7 ème' => ['organisees' => 7, 'autorisees' => 7],
    '8 ème' => ['organisees' => 5, 'autorisees' => 5],
    '1 ère' => ['organisees' => 1, 'autorisees' => 1],
    '2 ème' => ['organisees' => 1, 'autorisees' => 1],
    '3 ème' => ['organisees' => 1, 'autorisees' => 1],
    '4 ème' => ['organisees' => 1, 'autorisees' => 1],
];

// --- Demandes en attente ---
$stmt = $pdo->prepare("SELECT * FROM eleves WHERE statut_inscription = 'en_attente' ORDER BY date_inscription DESC");
$stmt->execute();
$demandes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Collège Saint-Théophile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <style>
        .navbar {
            background: linear-gradient(90deg, #0a58ca, #0a3d62);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--box-shadow-sm);
            transition: var(--transition);
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: var(--box-shadow); }
        .stat-card h3 { font-size: 2.2rem; margin: 0; color: var(--color-primary); }
        .table-actions a { margin-right: 0.5rem; }
        footer { text-align: center; margin-top: 3rem; padding: 1rem; color: #6c757d; border-top: 1px solid #dee2e6; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="ms-auto">
    <a href="admin_messages.php" class="btn btn-outline-light me-2">Messages <?= $messagesNonLus ? "($messagesNonLus)" : '' ?></a>
    <a href="admin_eleves.php" class="btn btn-outline-light me-2">Élèves</a>
    <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
        <a href="admin_manage.php" class="btn btn-outline-light me-2">Admins</a>
        <a href="inscription_admin.php" class="btn btn-outline-light me-2">Admin rapide</a>
    <?php endif; ?>
    <a href="login_admi.php" class="btn btn-light">Déconnexion</a>
</div>
</nav>
<div class="container" style="margin-top: 90px;">
    <!-- Ligne de statistiques -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card"><h3><?= $totalEleves ?></h3><p>Élèves inscrits</p></div>
        </div>
        <div class="col-md-3">
            <div class="stat-card"><h3><?= $demandesAttente ?></h3><p>Demandes en attente</p></div>
        </div>
        <div class="col-md-3">
            <div class="stat-card"><h3><?= $messagesNonLus ?></h3><p>Messages non lus</p></div>
        </div>
        <div class="col-md-3">
            <div class="stat-card"><h3><?= $totalAdmins ?></h3><p> Administrateurs</p></div>
        </div>
    </div>

    <!-- Demandes d'inscription -->
    <div class="card shadow mb-5">
        <div class="card-header bg-primary text-white"><h5 class="mb-0"> Demandes d'inscription à traiter</h5></div>
        <div class="card-body">
            <?php if (count($demandes) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>ID</th><th>Nom complet</th><th>Email</th><th>Classe</th><th>Option</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach ($demandes as $d): ?>
                    <tr>
                        <td><?= $d['id'] ?></td>
                        <td><?= htmlspecialchars($d['nom'].' '.$d['prenom'].' '.$d['postnom']) ?></td>
                        <td><?= htmlspecialchars($d['email']) ?></td>
                        <td><?= htmlspecialchars($d['classe_actuelle']) ?></td>
                        <td><?= htmlspecialchars($d['option_souhaitee']) ?></td>
                        <td class="table-actions">
                            <a href="traiter_inscription.php?id=<?= $d['id'] ?>&action=admis" class="btn btn-sm btn-success">✔ Admettre</a>
                            <a href="traiter_inscription.php?id=<?= $d['id'] ?>&action=non_admis" class="btn btn-sm btn-danger">✘ Refuser</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p class="text-muted">Aucune demande en attente.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tableau des effectifs -->
    <div class="card shadow">
        <div class="card-header bg-secondary text-white"><h5 class="mb-0"> Effectifs par classe et option</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>Année</th><th>Section</th><th>Classes organisées</th><th>Classes autorisées</th><th>Effectifs</th><th>Total</th></tr></thead>
                    <tbody>
                    <?php foreach ($effectifs as $e): 
                        $classe = $e['classe_actuelle'];
                        $option = $e['option_souhaitee'] ?: '-';
                        $org = $infosClasse[$classe]['organisees'] ?? 1;
                        $aut = $infosClasse[$classe]['autorisees'] ?? 1;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($classe) ?></td>
                        <td><?= htmlspecialchars($option) ?></td>
                        <td><?= $org ?></td>
                        <td><?= $aut ?></td>
                        <td><?= $e['nb_eleves'] ?></td>
                        <td><?= $totauxClasse[$classe] ?? $e['nb_eleves'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot><tr class="table-dark"><th colspan="5">Total général</th><th><?= $totalGeneral ?></th></tr></tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<footer>
    <p>© Jonas Ngalamulume, 2026 – Collège Saint-Théophile</p>
</footer>
</body>
</html>