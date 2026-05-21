<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE email = ?");
    $stmt->execute([$email]);
    $eleve = $stmt->fetch();

    if ($eleve && $password === $eleve['date_naissance']) {
        $_SESSION['eleve_id'] = $eleve['id'];
        $_SESSION['eleve_nom'] = $eleve['nom'] . ' ' . $eleve['prenom'];
        header('Location: espace_eleve.php');
        exit;
    } else {
        $error = 'Email ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion élève</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<section>
    <h1>Connexion Élève</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="input-box">
            <input type="email" name="email" placeholder="Adresse email" required>
            <i class="fa-solid fa-envelope"></i>
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="Mot de passe (date de naissance AAAA-MM-JJ)" required>
            <i class="fa-solid fa-eye"></i>
        </div>
        <div class="remember-forgot">
            <label><input type="checkbox"> Se souvenir de moi</label>
            <a href="#">Mot de passe oublié ?</a>
        </div>
        <button type="submit" class="Login-btn">Se connecter</button>
        <br><br>
        <a href="index.html"><button type="button" class="Login-btn">Retour à l'accueil</button></a>
        <div class="register-link">
            <p>Pas de compte ? <a href="formulaire_inscription.php">Inscription</a></p>
        </div>
    </form>
</section>
</body>
</html>