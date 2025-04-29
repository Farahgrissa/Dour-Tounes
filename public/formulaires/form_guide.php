<?php
require_once '../../config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $civilite = $_POST['civilite'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    $langue_maternelle = $_POST['langue_maternelle'];
    $telephone = $_POST['telephone'] ?? null;
    $region = $_POST['region'] ?? null;
    $langue1 = $_POST['langue1'];
    $langue2 = $_POST['langue2'] ?? null;
    $presentation = $_POST['presentation'];
    $message = $_POST['message'] ?? null;
    $date_inscription = date('Y-m-d H:i:s');

    // Validation des mots de passe
    if (strlen($password) < 8) {
        $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    if ($password !== $password_confirm) {
        $errors['password_confirm'] = "Les mots de passe ne correspondent pas.";
    }

    // Si aucune erreur
    if (empty($errors)) {
        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Upload des fichiers
        $photo = $_FILES['photo'];
        $cartePro = $_FILES['carte_pro'];

        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $photoPath = $targetDir . basename($photo["name"]);
        $cartePath = $targetDir . basename($cartePro["name"]);

        move_uploaded_file($photo["tmp_name"], $photoPath);
        move_uploaded_file($cartePro["tmp_name"], $cartePath);

        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO guides 
            (civilite, prenom, nom, email, password, langue_maternelle, telephone, region, langue1, langue2, presentation, photo, carte_pro, message, date_inscription) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->execute([
            $civilite, $prenom, $nom, $email, $hashedPassword,
            $langue_maternelle, $telephone, $region,
            $langue1, $langue2, $presentation, $photoPath, $cartePath, $message,$date_inscription
        ]);

        $confirmation = "Inscription réussie ! Merci pour votre confiance.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Guide Touristique</title>
    <link rel="stylesheet" href="../CSS/formulaireguide.css">
</head>
<body>

    
    <header>
        <div class="logo">
            <img src="../images/logos/logo.png" alt="Logo Tourisme">
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Panoramas</a></li>
                <li><a href="#">Activités</a></li>
                <li><a href="#">Contact</a></li>
                
            </ul>
        </nav>
        <div class="guide">
            <a href="../connexion.php">connexion</a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1><span>Inscription guide touristique</span></h1>
            <p>Rejoignez GuideYourTrip</p>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
    

    <section class="inscription">
        <div>
            <h2>Attirez plus de voyageurs et développez votre activité !</h2>
            <p class="tarif">💰 <strong>Paiement unique :</strong> seulement <span class="highlight">29 €</span> pour une inscription à vie !</p>
            <p class="details">Aucun abonnement. Aucun frais caché.</p>

            <h3>Pourquoi rejoindre Dour Tounes ?</h3>
            <ol>
                <li><strong>Visibilité mondiale :</strong> Présentez vos services à une large audience.</li>
                <li> <strong>Gagnez en notoriété :</strong> Mettez en avant vos compétences et expériences.</li>
            </ol>
        </div>
    </section>
    </section>
    <br>
    <br><br><br><br> <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<div class="container">
<div class="container">
    <h2>Les champs marqués d'une <span style="color: red;">*</span> sont obligatoires</h2>

    <?php if (isset($confirmation)) : ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($confirmation) ?></p>
    <?php endif; ?>

    <form action="../connexion.php" method="POST" enctype="multipart/form-data">

        
        <div class="form-group">
            <label for="civilite" class="required">Civilité</label>
            
            <input type="radio" name="civilite" value="Mr." required> Mr.
            <input type="radio" name="civilite" value="Mme" required> Mme
            
        </div>

        <div class="form-group">
            <label for="prenom" class="required">Prénom</label>
            <input type="text" name="prenom" required>
        </div>

        <div class="form-group">
            <label for="nom" class="required">Nom</label>
            <input type="text" name="nom" required>
        </div>

        <div class="form-group">
            <label for="email" class="required">Email de contact pour les touristes</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="langue_maternelle" class="required">Langue maternelle</label>
            <input type="text" name="langue_maternelle" required>
        </div>

        <div class="form-group">
            <label for="telephone">N° téléphone de contact</label>
            <input type="tel" name="telephone" placeholder="Saisir indicatif du pays">
        </div>

        <div class="form-group">
            <label for="region">Région ou ville de vos visites</label>
            <input type="text" name="region">
        </div>

        <div class="form-group">
    <label for="langue" class="required">Langues de visite</label>
    <input type="text" name="langue1" required placeholder="Langue principale">
    <input type="text" name="langue2" placeholder="Langue secondaire">
    </div>


        <div class="form-file" >
            <label for="presentation" class="required">Presentation</label>
            <textarea name="presentation" rows="2" minlength="10" placeholder="Ecrire quelques lignes de présentation qui apparaitront sur votre page dédiée" required></textarea>
        </div>
        <br>
        <br>
        <div class="form-file" >
            <label for="photo" class="required">Envoyer-nous une photo du guide </label>
            <input type="file" name="photo" required >
        </div>
        <br>
        <br>
        <div class="form-file">
            <label for="carte_pro" class="required">Envoyez votre carte professionnelle de guide </label>
            <input type="file" id="carte_pro" accept="image/*,application/pdf" required>
        </div>
        <br>
        <br>
        <div class="form-file">
            <label for="message">Message à notre attention</label>
            <textarea id="message" placeholder="Vous pouvez saisir ici un message à notre attention." rows="3"></textarea>
        </div>
        <br>
        <br>
        <div class="form-group">
    <label for="password" class="required">Créer un mot de passe</label>
    <input type="password" name="password" required minlength="8">
    <?php if (isset($errors['password'])): ?>
        <span class="error"><?= htmlspecialchars($errors['password']) ?></span>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="password_confirm" class="required">Confirmer le mot de passe</label>
    <input type="password" name="password_confirm" required>
    <?php if (isset($errors['password_confirm'])): ?>
        <span class="error"><?= htmlspecialchars($errors['password_confirm']) ?></span>
    <?php endif; ?>
</div>

        
        <button type="submit">Soumettre</button>
    </form>
</div>
<footer>
    <div class="footer-container">
        <div class="footer-section about">
            <h2>Dour Tounes</h2>
            <p>Rejoignez notre plateforme et attirez plus de voyageurs en quelques minutes. Partagez votre passion et faites découvrir la Tunisie.</p>
        </div>

        <div class="footer-section links">
            <h3>Liens utiles</h3>
            <ul>
                <li><a href="/dourtounes/index.php">Accueil</a></li>
                <li><a href="#">À propos</a></li>
                <li><a href="#">Nos guides</a></li>
                <li><a href="#">Avis</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <div class="footer-section contact">
            <h3>Contact</h3>
            <p>Email : <a href="mailto:contact@dour_tounes.tn">contact@dour_tounes.tn</a></p>
            <p>Téléphone : +33 1 23 45 67 89</p>
            <p>Adresse : 123, Rue des Voyages, Tunis, Tunis</p>
        </div>

        <div class="footer-section social">
            <h3>Suivez-nous</h3>
            <div class="social-icons">
                <a href="#"><img src="../images/logos/fb.webp" alt="Facebook"></a>
                <a href="#"><img src="../images/logos/mail.png" alt="email"></a>
                <a href="#"><img src="../images/logos/insta.jpeg" alt="Instagram"></a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 Dour Tounes | Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>