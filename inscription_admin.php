<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    header('Location: dashboard.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $postnom = trim($_POST['postnom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['phone'] ?? '');
    $sexe = $_POST['sexe'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'admin';

    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM administrateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO administrateurs (nom, prenom, postnom, email, telephone, sexe, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            try {
                $stmt->execute([$nom, $prenom, $postnom, $email, $telephone, $sexe, $hashed_password, $role]);
                $message = "Administrateur ajouté avec succès.";
            } catch (PDOException $e) {
                $error = "Erreur : " . $e->getMessage();
            }
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id != ? ORDER BY nom");
$stmt->execute([$_SESSION['admin_id']]);
$admins = $stmt->fetchAll();
?>
<!-- À partir de <!DOCTYPE html> -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a57ca99, #0a3d6267);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: var(--font-body);
        }
        .admin-card {
            background: rgba(13, 42, 155, 0.33);
            backdrop-filter: blur(12px);
            border-radius: 2rem;
            padding: 2rem;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .admin-card h2 {
            color: white;
            font-family: var(--font-heading);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-label {
            color: white;
            font-weight: 500;
        }
        .form-control, .form-select {
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 2rem;
            padding: 0.6rem 1.2rem;
        }
        .btn-submit {
            background: linear-gradient(135deg, #ffc107, #ff9f00);
            border: none;
            border-radius: 2rem;
            padding: 0.6rem;
            font-weight: bold;
            color: #1a365d;
            width: 100%;
            transition: 0.3s;
        }
        .btn-submit:hover {
            transform: scale(1.02);
            background: #fefefe70;
        }
        .btn-secondary {
            border-radius: 2rem;
        }
        .alert {
            border-radius: 1rem;
        }
    </style>
</head>
<body>
<div class="admin-card">
    <h2>Ajouter un administrateur</h2>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Postnom</label>
            <input type="text" name="postnom" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="tel" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Sexe</label><br>
            <label class="form-check-label text-white me-3"><input type="radio" name="sexe" value="Masculin"> Masculin</label>
            <label class="form-check-label text-white"><input type="radio" name="sexe" value="Féminin"> Féminin</label>
        </div>
        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Rôle</label>
            <select name="role" class="form-select">
                <option value="admin">Administrateur</option>
                <option value="super_admin">Super administrateur</option>
            </select>
        </div>
        <button type="submit" name="add_admin" class="btn-submit">Ajouter</button>
        <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Retour</a>
    </form>
</div>
</body>
</html>