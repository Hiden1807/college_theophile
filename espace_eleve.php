<?php
session_start();
require_once 'config.php';

// Vérifier que l'élève est connecté
if (!isset($_SESSION['eleve_id'])) {
    header('Location: login_eleve.php');
    exit;
}

$eleve_id = $_SESSION['eleve_id'];

// Récupérer toutes les informations de l'élève
$stmt = $pdo->prepare("SELECT * FROM eleves WHERE id = ?");
$stmt->execute([$eleve_id]);
$eleve = $stmt->fetch();

if (!$eleve) {
    session_destroy();
    header('Location: login_eleve.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon espace – Collège Saint Théophile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="css/global.css">
</head>
<body>
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
        <div class="col d-flex align-items-center">
            <img src="images/logo.jpg" width="50" height="50" class="me-2">
            <a class="navbar-brand fw-bold" href="index.html">COLLEGE SAINT THEOPHILE</a>
        </div>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="logout_eleve.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>
</div>
<br><br><br><br>
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2>Bienvenue, <?= htmlspecialchars($eleve['prenom'] . ' ' . $eleve['nom']) ?></h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Mes informations</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nom complet :</strong> <?= htmlspecialchars($eleve['nom'] . ' ' . $eleve['prenom'] . ' ' . $eleve['postnom']) ?></li>
                        <li class="list-group-item"><strong>Date de naissance :</strong> <?= htmlspecialchars($eleve['date_naissance']) ?></li>
                        <li class="list-group-item"><strong>Lieu de naissance :</strong> <?= htmlspecialchars($eleve['lieu_naissance']) ?></li>
                        <li class="list-group-item"><strong>Sexe :</strong> <?= htmlspecialchars($eleve['sexe']) ?></li>
                        <li class="list-group-item"><strong>Classe actuelle :</strong> <?= htmlspecialchars($eleve['classe_actuelle']) ?></li>
                        <li class="list-group-item"><strong>Option souhaitée :</strong> <?= htmlspecialchars($eleve['option_souhaitee']) ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4>Statut de l'inscription</h4>
                    <?php
                    $statut = $eleve['statut_inscription'];
                    $badge = '';
                    if ($statut == 'admis') $badge = 'success';
                    elseif ($statut == 'non_admis') $badge = 'danger';
                    else $badge = 'warning';
                    ?>
                    <div class="alert alert-<?= $badge ?>">
                        <strong>Décision :</strong> 
                        <?php
                        if ($statut == 'admis') echo 'Félicitations ! Vous êtes admis(e) au Collège Saint Théophile.';
                        elseif ($statut == 'non_admis') echo 'Nous vous remercions de votre participation. Non admis(e) cette année.';
                        else echo 'Votre dossier est en cours d’examen. Revenez plus tard.';
                        ?>
                    </div>
                    <?php if ($statut == 'admis'): ?>
                        <div class="mt-3">
                            <h5>Prochaines étapes</h5>
                            <ul>
                                <li>Finaliser votre dossier administratif</li>
                                <li>Payer la contribution scolaire</li>
                                <li>Rentrée prévue le 15 septembre</li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>