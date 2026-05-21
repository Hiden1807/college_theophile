<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    header('Location: dashboard.php');
    exit;
}
require_once 'config.php';

// Ajout ou modification
$message = '';
$edit_id = $_GET['edit'] ?? 0;
$edit_admin = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_admin = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_admin'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $postnom = $_POST['postnom'];
    $email = $_POST['email'];
    $telephone = $_POST['phone'];
    $sexe = $_POST['sexe'];
    $role = $_POST['role'];
    $password = $_POST['password'] ?? '';
    $id_admin = $_POST['id_admin'] ?? 0;

    if ($id_admin) {
        // Modification
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE administrateurs SET nom=?, prenom=?, postnom=?, email=?, telephone=?, sexe=?, role=?, mot_de_passe=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $postnom, $email, $telephone, $sexe, $role, $hash, $id_admin]);
        } else {
            $sql = "UPDATE administrateurs SET nom=?, prenom=?, postnom=?, email=?, telephone=?, sexe=?, role=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $postnom, $email, $telephone, $sexe, $role, $id_admin]);
        }
        $message = "Administrateur modifié.";
    } else {
        // Ajout
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO administrateurs (nom, prenom, postnom, email, telephone, sexe, mot_de_passe, role) VALUES (?,?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $postnom, $email, $telephone, $sexe, $hash, $role]);
        $message = "Administrateur ajouté.";
    }
    header('Location: admin_manage.php');
    exit;
}

// Liste des admins (sauf soi-même)
$stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id != ? ORDER BY nom");
$stmt->execute([$_SESSION['admin_id']]);
$admins = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des administrateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <style>
        .admin-form { background: #f8f9fa; padding: 1.5rem; border-radius: 1rem; margin-bottom: 2rem; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestion des administrateurs</h2>
        <a href="dashboard.php" class="btn btn-secondary">← Retour</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout / édition -->
    <div class="admin-form">
        <h4><?= $edit_admin ? 'Modifier' : 'Ajouter' ?> un administrateur</h4>
        <form method="post">
            <?php if ($edit_admin): ?>
                <input type="hidden" name="id_admin" value="<?= $edit_admin['id'] ?>">
            <?php endif; ?>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($edit_admin['nom'] ?? '') ?>" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($edit_admin['prenom'] ?? '') ?>" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Postnom</label>
                    <input type="text" name="postnom" class="form-control" value="<?= htmlspecialchars($edit_admin['postnom'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($edit_admin['email'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label>Téléphone</label>
                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($edit_admin['telephone'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-2">
                    <label>Sexe</label>
                    <select name="sexe" class="form-select">
                        <option value="Masculin" <?= ($edit_admin['sexe'] ?? '') == 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                        <option value="Féminin" <?= ($edit_admin['sexe'] ?? '') == 'Féminin' ? 'selected' : '' ?>>Féminin</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Rôle</label>
                    <select name="role" class="form-select">
                        <option value="admin" <?= ($edit_admin['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Administrateur</option>
                        <option value="super_admin" <?= ($edit_admin['role'] ?? '') == 'super_admin' ? 'selected' : '' ?>>Super administrateur</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label><?= $edit_admin ? 'Nouveau mot de passe (laisser vide pour conserver)' : 'Mot de passe' ?></label>
                    <input type="password" name="password" class="form-control" <?= $edit_admin ? '' : 'required' ?>>
                </div>
            </div>
            <button type="submit" name="save_admin" class="btn btn-primary"><?= $edit_admin ? 'Modifier' : 'Ajouter' ?></button>
            <?php if ($edit_admin): ?>
                <a href="admin_manage.php" class="btn btn-secondary">Annuler</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Liste des administrateurs -->
    <h4>Liste des administrateurs</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>ID</th><th>Nom complet</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= htmlspecialchars($a['prenom'].' '.$a['nom'].' '.$a['postnom']) ?></td>
                    <td><?= htmlspecialchars($a['email']) ?></td>
                    <td><?= $a['role'] ?></td>
                    <td>
                        <a href="admin_manage.php?edit=<?= $a['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="delete_admin.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer définitivement cet administrateur ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>