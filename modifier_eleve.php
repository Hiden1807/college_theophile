<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admi.php');
    exit;
}
require_once 'config.php';

$id = intval($_GET['id'] ?? 0);
$eleve = null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE id = ?");
    $stmt->execute([$id]);
    $eleve = $stmt->fetch();
}
if (!$eleve) {
    header('Location: admin_eleves.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $postnom = $_POST['postnom'];
    $email = $_POST['email'];
    $classe = $_POST['classe_actuelle'];
    $option = $_POST['option_souhaitee'];
    $statut = $_POST['statut_inscription'];

    $sql = "UPDATE eleves SET nom=?, prenom=?, postnom=?, email=?, classe_actuelle=?, option_souhaitee=?, statut_inscription=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nom, $prenom, $postnom, $email, $classe, $option, $statut, $id])) {
        $message = "<div class='alert alert-success'>Élève modifié avec succès.</div>";
        // Recharger les données
        $stmt = $pdo->prepare("SELECT * FROM eleves WHERE id = ?");
        $stmt->execute([$id]);
        $eleve = $stmt->fetch();
    } else {
        $message = "<div class='alert alert-danger'>Erreur lors de la modification.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un élève</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3>✏️ Modifier l'élève #<?= $eleve['id'] ?></h3>
        </div>
        <div class="card-body">
            <?= $message ?>
            <form method="post">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($eleve['nom']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($eleve['prenom']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Postnom</label>
                        <input type="text" name="postnom" class="form-control" value="<?= htmlspecialchars($eleve['postnom']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($eleve['email']) ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Classe actuelle</label>
                        <select name="classe_actuelle" class="form-select">
                            <?php foreach (['7 ème','8 ème','1 ère','2 ème','3 ème','4 ème'] as $c): ?>
                                <option value="<?= $c ?>" <?= $eleve['classe_actuelle'] == $c ? 'selected' : '' ?>><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Option</label>
                        <input type="text" name="option_souhaitee" class="form-control" value="<?= htmlspecialchars($eleve['option_souhaitee']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Statut</label>
                        <select name="statut_inscription" class="form-select">
                            <option value="en_attente" <?= $eleve['statut_inscription']=='en_attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="admis" <?= $eleve['statut_inscription']=='admis' ? 'selected' : '' ?>>Admis</option>
                            <option value="non_admis" <?= $eleve['statut_inscription']=='non_admis' ? 'selected' : '' ?>>Non admis</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="admin_eleves.php" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>