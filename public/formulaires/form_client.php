<?php
require_once '../../config.php'; 
$errors = [];
$formData = [
    'civilite' => '',
    'prenom' => '',
    'nom' => '',
    'email' => '',
    'telephone' => '',
    'destination' => '',
    'date_arrivee' => '',
    'date_depart' => '',
    'interets' => [],
    'langue1' => '',
    'langue2' => '',
    'besoins' => '',
    'newsletter' => 0,
    'password' => '',
    'password_confirm' => '',
    'conditions' => 0
];

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialiser le tableau formData avec les données du formulaire
    $formData = [
        'civilite' => $_POST['civilite'] ?? '',
        'prenom' => $_POST['prenom'] ?? '',
        'nom' => $_POST['nom'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telephone' => $_POST['telephone'] ?? '',
        'destination' => $_POST['destination'] ?? '',
        'date_arrivee' => $_POST['date_arrivee'] ?? '',
        'date_depart' => $_POST['date_depart'] ?? '',
        'interets' => $_POST['interets'] ?? [],
        'langue1' => $_POST['langue1'] ?? '',
        'langue2' => $_POST['langue2'] ?? '',
        'besoins' => $_POST['besoins'] ?? '',
        'newsletter' => isset($_POST['newsletter']) ? 1 : 0,
        'password' => $_POST['password'] ?? '',
        'password_confirm' => $_POST['password_confirm'] ?? '',
        'conditions' => isset($_POST['conditions']) ? 1 : 0
    ];

    // Validation des champs
    if (empty($formData['prenom'])) {
        $errors['prenom'] = "Le prénom est requis.";
    }

    if (empty($formData['nom'])) {
        $errors['nom'] = "Le nom est requis.";
    }

    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email n'est pas valide.";
    }

    if (empty($formData['civilite'])) {
        $errors['civilite'] = "La civilité est requise.";
    }

    if (empty($formData['password']) || strlen($formData['password']) < 6) {
        $errors['password'] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    // Vérification de la confirmation du mot de passe
    if ($formData['password'] !== $formData['password_confirm']) {
        $errors['password_confirm'] = "Les mots de passe ne correspondent pas.";
    }

    // Vérification de la case des conditions générales
    if (empty($formData['conditions'])) {
        $errors['conditions'] = "Vous devez accepter les conditions générales.";
    }

    // Vérification des dates
    if (!empty($formData['date_arrivee']) && !empty($formData['date_depart'])) {
        $dateArrivee = strtotime($formData['date_arrivee']);
        $dateDepart = strtotime($formData['date_depart']);
        if ($dateArrivee > $dateDepart) {
            $errors['dates'] = "La date de départ doit être après la date d'arrivée.";
        }
    }

    // Si aucune erreur, procéder à l'insertion
    if (empty($errors)) {
        // Préparer la requête d'insertion
        $stmt = $pdo->prepare(
            "INSERT INTO clients (civilite, prenom, nom, email, telephone, destination, date_arrivee, date_depart, interets, langue1, langue2, besoins, newsletter, password, conditions)
            VALUES (:civilite, :prenom, :nom, :email, :telephone, :destination, :date_arrivee, :date_depart, :interets, :langue1, :langue2, :besoins, :newsletter, :password, :conditions)"
        );

        // Crypter le mot de passe avant l'insertion
        $passwordHash = password_hash($formData['password'], PASSWORD_BCRYPT);

        // Exécuter la requête avec les valeurs du formulaire
        $stmt->execute([
            ':civilite' => $formData['civilite'],
            ':prenom' => $formData['prenom'],
            ':nom' => $formData['nom'],
            ':email' => $formData['email'],
            ':telephone' => $formData['telephone'],
            ':destination' => $formData['destination'],
            ':date_arrivee' => $formData['date_arrivee'],
            ':date_depart' => $formData['date_depart'],
            ':interets' => implode(',', $formData['interets']),
            ':langue1' => $formData['langue1'],
            ':langue2' => $formData['langue2'],
            ':besoins' => $formData['besoins'],
            ':newsletter' => $formData['newsletter'],
            ':password' => $passwordHash,
            ':conditions' => $formData['conditions']
        ]);

        // Redirection après l'inscription
        header('Location: confirmation.php'); // Remplace par la page que tu veux afficher après inscription
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Touriste</title>
    <link rel="stylesheet" href="../CSS/formulairetourist.css">
</head>
<body>

    <header>
        <div class="logo">
            <img src="../images/logos/logo.png" alt="Logo Tourisme">
        </div>
        <nav>
            <ul>
                <li><a href="/dourtounes/index.php">Accueil</a></li>
                <li><a href="#">Destinations</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
        <div class="guide">
            <a href="../connexion.php">connexion</a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1><span>Espace voyageurs</span></h1>
            <p>Planifiez votre voyage avec Dour Tounes</p>
        </div>
    </section>

    <section class="benefits">
        <div>
            <h2>Pourquoi créer un compte voyageur ?</h2>
            <ol>
                <li><strong>Accès privilégié :</strong> Recevez des offres exclusives et des recommandations personnalisées</li>
                <li><strong>Réservation facile :</strong> Enregistrez vos favoris et planifiez vos activités</li>
                <li><strong>Gratuit :</strong> Création de compte et utilisation sans frais</li>
            </ol>
        </div>
    </section>

    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <h3>Erreurs dans le formulaire :</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <h2>Les champs marqués d'une <span style="color: red;">*</span> sont obligatoires</h2>
        <form action="form_client.php" method="POST">
            <div class="form-group">
                <label for="civilite" class="required">Civilité</label>
                <input type="radio" name="civilite" value="Mr." <?= $formData['civilite'] === 'Mr.' ? 'checked' : '' ?> required> Mr.
                <input type="radio" name="civilite" value="Mme" <?= $formData['civilite'] === 'Mme' ? 'checked' : '' ?> required> Mme
                <?php if (isset($errors['civilite'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['civilite']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="prenom" class="required">Prénom</label>
                <input type="text" name="prenom" value="<?= htmlspecialchars($formData['prenom']) ?>" required>
                <?php if (isset($errors['prenom'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['prenom']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="nom" class="required">Nom</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($formData['nom']) ?>" required>
                <?php if (isset($errors['nom'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['nom']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email" class="required">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($formData['email']) ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" name="telephone" value="<?= htmlspecialchars($formData['telephone']) ?>" placeholder="Saisir indicatif du pays">
            </div>

            <div class="form-group">
                <label for="destination">Destination souhaitée</label>
                <select name="destination">
                    <option value="">Choisir une destination</option>
                    <option value="mer" <?= $formData['destination'] === 'mer' ? 'selected' : '' ?>>mer</option>
                    <option value="nature" <?= $formData['destination'] === 'nature' ? 'selected' : '' ?>>nature</option>
                    <option value="desert" <?= $formData['destination'] === 'desert' ? 'selected' : '' ?>>desert</option>
                    <option value="monument" <?= $formData['destination'] === 'monument' ? 'selected' : '' ?>>monument</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date_arrivee">Date d'arrivée prévue</label>
                <input type="date" name="date_arrivee" value="<?= htmlspecialchars($formData['date_arrivee']) ?>">
            </div>

            <div class="form-group">
                <label for="date_depart">Date de départ prévue</label>
                <input type="date" name="date_depart" value="<?= htmlspecialchars($formData['date_depart']) ?>">
            </div>

            <div class="form-group">
                <label for="interets">Centres d'intérêt</label>
                <div class="checkbox-group">
                    <input type="checkbox" name="interets[]" value="Plage" <?= in_array('Plage', $formData['interets']) ? 'checked' : '' ?>> Plage
                    <input type="checkbox" name="interets[]" value="Culture" <?= in_array('Culture', $formData['interets']) ? 'checked' : '' ?>> Culture
                    <input type="checkbox" name="interets[]" value="Gastronomie" <?= in_array('Gastronomie', $formData['interets']) ? 'checked' : '' ?>> Gastronomie
                    <input type="checkbox" name="interets[]" value="Shopping" <?= in_array('Shopping', $formData['interets']) ? 'checked' : '' ?>> Shopping
                </div>
            </div>

            <div class="form-group">
                <label for="langues">Langues parlées</label>
                <input type="text" name="langue1" value="<?= htmlspecialchars($formData['langue1']) ?>" placeholder="Langue principale">
                <input type="text" name="langue2" value="<?= htmlspecialchars($formData['langue2']) ?>" placeholder="Autre langue">
            </div>

            <div class="form-group">
                <label for="besoins">Besoins spécifiques</label>
                <textarea name="besoins" rows="3" placeholder="Mobilité réduite, allergies alimentaires, etc."><?= htmlspecialchars($formData['besoins']) ?></textarea>
            </div>

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

            <div class="form-group">
                <input type="checkbox" name="newsletter" <?= $formData['newsletter'] ? 'checked' : '' ?>> 
                <label for="newsletter">Je souhaite recevoir la newsletter et des offres spéciales</label>
            </div>

            <div class="form-group">
                <input type="checkbox" name="conditions" <?= empty($errors) ? '' : 'checked' ?> required> 
                <label for="conditions" class="required">J'accepte les conditions générales d'utilisation</label>
                <?php if (isset($errors['conditions'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['conditions']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <button type="submit">S'inscrire</button>
            </div>
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
