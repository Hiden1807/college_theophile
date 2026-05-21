<?php
require_once 'config.php';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_complet = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $service = $_POST['service'] ?? '';
    $message = trim($_POST['message'] ?? '');

    if (empty($nom_complet) || empty($email) || empty($message)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } else {
        $sql = "INSERT INTO messages (nom_complet, email, service, message) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$nom_complet, $email, $service, $message]);
            $success = "Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'envoi : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Collège St Théophile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/mon projet.css">
    <link rel="stylesheet" href="css/global.css"> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .alert { margin-bottom: 1.5rem; border-radius: 1rem; }
        .btn-submit { transition: 0.3s; }
        .btn-submit:hover { transform: translateY(-3px); }
    </style>
</head>
<body>
    <main class="contact-card">
        <header class="form-header">
            <div class="school-logo"></div>
            <h1>Collège St Théophile</h1>
            <p>L'excellence au service de l'éducation</p>
            <div class="school-contact-info">
                <p><strong><a href="https://www.google.fr/maps/place/College+Saint+Theophile+de+Lemba/">Adresse</a> :</strong> 123, Avenue kibali n°17, Quartier Madrandele, Province Kinshasa</p>
                <p><strong><a href="tel:+243831966097">Contact</a> :</strong> +243 831 966 097</p>
            </div>
        </header>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <div class="input-box">
                    <input type="text" id="fullname" name="fullname" required>
                    <label for="fullname">Nom complet</label>
                </div>
                <div class="input-box">
                    <input type="email" id="email" name="email" required>
                    <label for="email">Adresse Email</label>
                </div>
            </div>
            <div class="input-box">
                <select id="service" name="service" required>
                    <option value="" disabled selected hidden></option> 
                    <option value="inscription">Service des Inscriptions</option>
                    <option value="compta">Comptabilité</option>
                    <option value="direction">Direction Pédagogique</option>
                </select>
                <label for="service">Département concerné</label>
            </div>
            <div class="input-box">
                <textarea id="message" name="message" rows="3" required></textarea>
                <label for="message">Votre message...</label>
            </div>
            <button type="submit" class="btn-submit">Envoyer</button>
        </form>
        <a href="index.html"><button class="btn-submit" style="background: #6c757d;">Retour à l'accueil</button></a>
    </main>
</body>
</html>