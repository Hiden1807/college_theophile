<?php
session_start();
require_once 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE email = ? OR nom = ?");
    $stmt->execute([$email, $email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['mot_de_passe'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_role'] = $admin['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a58ca, #0a3d62);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            padding: 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 35px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .login-card h2 {
            color: white;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-label {
            color: white;
            font-weight: 500;
        }
        .form-control {
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 2rem;
            padding: 0.6rem 1.2rem;
        }
        .btn-login {
            background: linear-gradient(135deg, #ffc107, #ff9f00);
            border: none;
            border-radius: 2rem;
            padding: 0.6rem;
            font-weight: bold;
            color: #1a365d;
            width: 100%;
            transition: 0.3s;
        }
        .btn-login:hover {
            transform: scale(1.02);
            background: #ffc107;
        }
        .alert {
            border-radius: 1rem;
        }
    </style>
</head>
<body>
<div class="login-card">
    <h2>Administration</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Email ou nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn-login">Se connecter</button>
    </form>
</div>
</body>
</html>