<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admi.php');
    exit;
}
require_once 'config.php';

// Filtres
$statut_filter = $_GET['statut'] ?? 'tous';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM eleves WHERE 1=1";
$params = [];

if ($statut_filter !== 'tous') {
    $sql .= " AND statut_inscription = ?";
    $params[] = $statut_filter;
}
if (!empty($search)) {
    $sql .= " AND (nom LIKE ? OR prenom LIKE ? OR email LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}
$sql .= " ORDER BY date_inscription DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$eleves = $stmt->fetchAll();

// Statistiques pour les onglets
$total_tous = $pdo->query("SELECT COUNT(*) FROM eleves")->fetchColumn();
$total_attente = $pdo->query("SELECT COUNT(*) FROM eleves WHERE statut_inscription = 'en_attente'")->fetchColumn();
$total_admis = $pdo->query("SELECT COUNT(*) FROM eleves WHERE statut_inscription = 'admis'")->fetchColumn();
$total_non_admis = $pdo->query("SELECT COUNT(*) FROM eleves WHERE statut_inscription = 'non_admis'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des élèves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <style>
        .filter-btn { border-radius: 2rem; margin-right: 0.5rem; }
        .table-actions .btn { margin: 0 0.2rem; }
        .search-box { max-width: 300px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestion des inscriptions</h2>
        <a href="dashboard.php" class="btn btn-secondary">← Retour au tableau de bord</a>
    </div>

    <!-- Filtres par statut -->
    <div class="mb-3">
        <a href="?statut=tous&search=<?= urlencode($search) ?>" class="btn <?= $statut_filter=='tous' ? 'btn-primary' : 'btn-outline-primary' ?> filter-btn">Tous (<?= $total_tous ?>)</a>
        <a href="?statut=en_attente&search=<?= urlencode($search) ?>" class="btn <?= $statut_filter=='en_attente' ? 'btn-warning' : 'btn-outline-warning' ?> filter-btn">En attente (<?= $total_attente ?>)</a>
        <a href="?statut=admis&search=<?= urlencode($search) ?>" class="btn <?= $statut_filter=='admis' ? 'btn-success' : 'btn-outline-success' ?> filter-btn">Admis (<?= $total_admis ?>)</a>
        <a href="?statut=non_admis&search=<?= urlencode($search) ?>" class="btn <?= $statut_filter=='non_admis' ? 'btn-danger' : 'btn-outline-danger' ?> filter-btn">Non admis (<?= $total_non_admis ?>)</a>
    </div>

    <!-- Barre de recherche -->
    <form method="GET" class="mb-4 d-flex gap-2">
        <input type="hidden" name="statut" value="<?= htmlspecialchars($statut_filter) ?>">
        <input type="text" name="search" class="form-control search-box" placeholder="Rechercher par nom, prénom ou email" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Rechercher</button>
        <?php if ($search): ?>
            <a href="?statut=<?= $statut_filter ?>" class="btn btn-secondary">Réinitialiser</a>
        <?php endif; ?>
    </form>

    <!-- Tableau des élèves -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Nom complet</th><th>Email</th><th>Classe</th><th>Option</th><th>Statut</th><th>Date inscription</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($eleves) == 0): ?>
                    <tr><td colspan="8" class="text-center">Aucun élève trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach ($eleves as $e): ?>
                    <tr>
                        <td><?= $e['id'] ?></td>
                        <td><?= htmlspecialchars($e['nom'].' '.$e['prenom'].' '.$e['postnom']) ?></td>
                        <td><?= htmlspecialchars($e['email']) ?></td>
                        <td><?= htmlspecialchars($e['classe_actuelle']) ?></td>
                        <td><?= htmlspecialchars($e['option_souhaitee']) ?></td>
                        <td>
                            <?php
                                $badge = 'secondary';
                                if ($e['statut_inscription'] == 'admis') $badge = 'success';
                                elseif ($e['statut_inscription'] == 'non_admis') $badge = 'danger';
                                else $badge = 'warning';
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= $e['statut_inscription'] ?></span>
                        </td>
                        <td><?= $e['date_inscription'] ?></td>
                        <td class="table-actions">
                            <a href="modifier_eleve.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                            <a href="supprimer_eleve.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer définitivement cet élève ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>