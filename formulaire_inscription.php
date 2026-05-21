<?php
require_once 'config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage simple
    $nom          = $_POST['nom'] ?? '';
    $prenom       = $_POST['prenom'] ?? '';
    $postnom      = $_POST['postnom'] ?? '';
    $date_naissance = $_POST['birth_date'] ?? null;
    $lieu_naissance = $_POST['lieuNaissance'] ?? '';
    $sexe         = $_POST['sexe'] ?? '';
    $email        = $_POST['email']??'';
    $nationalite  = $_POST['nationalite'] ?? '';
    $province     = $_POST['province'] ?? '';
    $adresse      = $_POST['adresse'] ?? '';
    $tel_eleve    = $_POST['tel_eleve'] ?? '';
    $tel_tuteur   = $_POST['tel_tuteur'] ?? '';
    $nom_pere     = $_POST['parent_pere'] ?? '';
    $nom_mere     = $_POST['parent_mere'] ?? '';
    $tuteurs      = $_POST['tuteurs'] ?? '';
    $classe       = $_POST['classe_actuelle'] ?? '';
    $option       = $_POST['section'] ?? '';

    // Gestion de l'upload de la photo
    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = 'uploads/';
        if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);
        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photoPath = $uploadsDir . uniqid() . '.' . $extension;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    $sql = "INSERT INTO eleves (nom, prenom, postnom, date_naissance, lieu_naissance, sexe,email, nationalite, province_origine, adresse_complete, telephone_eleve, telephone_tuteur, nom_pere, nom_mere, tuteurs, classe_actuelle, option_souhaitee, photo) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([$nom, $prenom, $postnom, $date_naissance, $lieu_naissance, $sexe,$email, $nationalite, $province, $adresse, $tel_eleve, $tel_tuteur, $nom_pere, $nom_mere, $tuteurs, $classe, $option, $photoPath]);
        $message = "<div class='alert alert-success'>Inscription enregistrée avec succès !</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulaire d'inscription scolaire</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style-3.css">
  <link rel="stylesheet" href="css/global.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body class="bg-light">
  <div class="containner-fuid">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow  border border-primay  border-4 justify-content-center align-items-center">
    
      
      <div class="col d-flex align-items-center ">
        <img src="images/logo.jpg" class = "img-fluid" class="me-2" width="50px" height="50px">
        <a class="navbar-brand fw-bold d-md-block" href="index.html">   COLLEGE SAINT THEOPHILE</a>
      </div>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon" ></span>
      </button>
      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.html">Accueil</a></li>
          <li class="nav-item"><a class="nav-link active" href="apropos.html">À propos</a></li>
          <li class="nav-item"><a class="nav-link active" href="login_eleve.php">Connexion-élève</a></li>
          <li class="nav-item"><a class="nav-link active" href="#">Inscription</a></li>
          <li class="nav-item"><a class="nav-link active" href="galerie.html">Galerie</a></li>
          <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </nav>   
  </div><br><br>
  <div class="container py-5">
    <div class="container-wrapper p-4">
      <div class="text-center header position-relative mb-4">
        <h2 class="mb-4" style="color:rgb(255, 255, 255);">FORMULAIRE D'INSCRIPTION</h2>
        <p style="color:rgb(255, 255, 255);">Pour une formation de haute qualité et une éducation assurée</p>
      </div>
      <form method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control">
          </div>
          <div class="col-md-4">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="form-control">
          </div>
          <div class="col-md-4">
            <label for="postnom">Post-nom</label>
            <input type="text" id="postnom" name="postnom" class="form-control">
          </div>

          <div class="col-md-4">
            <label for="birth_date">Date de naissance</label>
            <input type="date" id="birth_date" name="birth_date" class="form-control">
          </div>
          <div class="col-md-4">
            <label for="lieuNaissance">Lieu de naissance</label>
            <input type="text" id="lieuNaissance" name="lieuNaissance" class="form-control">
          </div>
          <div class="col-md-4">
            <label for="sexe">Sexe</label>
            <select id="sexe" name="sexe" class="form-select">
              <option value="M">M</option>
              <option value="F">F</option>
            </select>
          </div>

          <div class="col-md-4">
            <label for="Email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label for="section">Option</label>
            <input type="text" id="section" name="section" class="form-control">
          </div>
          <div class="col-md-4">
            <label for="classe_actuelle">Classe actuelle</label>
            <input id="classe_actuelle" name="classe_actuelle" class="form-control">
          </div>
          <div class="col-md-4">
            <label for="nationalite">Nationalité</label>
            <input id="nationalite" name="nationalite" class="form-control">
          </div>

          <div class="col-md-6">
            <label for="province">Province d'origine</label>
            <input id="province" name="province" class="form-control">
          </div>
          <div class="col-md-6">
            <label for="adresse">Adresse complète</label>
            <input id="adresse" name="adresse" class="form-control">
          </div>

          <div class="col-md-6">
            <label for="tel_eleve">Numéro de téléphone (élève)</label>
            <input type="tel" id="tel_eleve" name="tel_eleve" class="form-control">
          </div>
          <div class="col-md-6">
            <label for="tel_tuteur">Numéro de téléphone (tuteur)</label>
            <input type="tel" id="tel_tuteur" name="tel_tuteur" class="form-control">
          </div>

          <div class="col-12">
            <label for="parents">Noms des parents</label>
            <input type="text" id="parent_pere" name="parent_pere" placeholder="Nom du père" class="form-control mb-2">
            <input type="text" id="parent_mere" name="parent_mere" placeholder="Nom de la mère" class="form-control">
          </div>

          <div class="col-md-6">
            <label for="tuteurs">Tuteurs de l'élève</label>
            <input type="text" id="tuteurs" name="tuteurs" class="form-control">
          </div>
          <div class="col-md-6">
            <label for="photo">Photo passeport</label>
            <input type="file" id="photo" name="photo" class="form-control">
          </div>

          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-success">S'inscrire</button>
          </div>
        </div>
      </form>

    </div>
  </div>
</body>
</html>