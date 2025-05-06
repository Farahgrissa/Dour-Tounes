<?php
session_start();
require_once '../../config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère la note et le commentaire
    $note = intval($_POST['note'] ?? 0);
    $commentaire = trim($_POST['commentaire']);

    // Vérifie que la note est valide et que le commentaire n'est pas vide
    if ($note >= 1 && $note <= 10 && !empty($commentaire)) {
        $date_publication = date('Y-m-d H:i:s'); // Date et heure actuelles
        
        // Gestion des fichiers téléchargés (uniquement les images)
        $mediaPaths = [];
        if (isset($_FILES['media']) && !empty($_FILES['media']['name'][0])) {
            // Créer un dossier pour les médias si nécessaire
            $uploadDir = '../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Parcourir les fichiers et les déplacer dans le dossier de téléchargement
            foreach ($_FILES['media']['tmp_name'] as $index => $tmpName) {
                $fileName = $_FILES['media']['name'][$index];
                $fileTmpPath = $_FILES['media']['tmp_name'][$index];
                $fileSize = $_FILES['media']['size'][$index];
                $fileType = $_FILES['media']['type'][$index];

                // Vérifie si le fichier est une image (tu peux ajouter d'autres types si nécessaire)
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($fileType, $allowedTypes)) {
                    $destination = $uploadDir . basename($fileName);
                    if (move_uploaded_file($fileTmpPath, $destination)) {
                        $mediaPaths[] = $destination; // Enregistre le chemin du fichier
                    }
                } else {
                    $message = "Seules les images sont autorisées.";
                    break;
                }
            }
        }

        // Convertir les chemins des images en une chaîne séparée par des virgules
        $imagePaths = implode(',', $mediaPaths);

        // Insertion de l'avis dans la table avis avec les chemins d'image
        $stmt = $pdo->prepare("INSERT INTO avis (note, commentaire, date_publication, image_paths) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$note, $commentaire, $date_publication, $imagePaths])) {
            $message = "Votre avis a été publié avec succès.";
        } else {
            $message = "Erreur lors de la publication.";
        }
    } else {
        $message = "Veuillez remplir tous les champs correctement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dour Tounes - Nouvelle Publication</title>
    <link rel="stylesheet" href="../CSS/avis.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        // Validation côté client pour le commentaire
        function validateForm() {
            const commentaire = document.querySelector('textarea[name="commentaire"]').value;
            if (commentaire.trim() === "") {
                alert("Veuillez entrer un commentaire.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="logo">
            <img src="./images/logos/logo1.png" alt="Logo Tourisme" />
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#panoramas">Destinations</a></li>
                <li><a href="#activites">Activités</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <div class="guide">
            <a href="./public/connexion.php">Connexion</a>
        </div>
    </header>

    <div class="publication-container">
        <header class="publication-header">
            <h1>Dour Tounes</h1>
            <div class="user-profile">
                <span class="username"><?= htmlspecialchars($_SESSION['nom'] ?? 'Touriste') ?></span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>

        <main class="publication-content">
            <?php if (!empty($message)): ?>
                <p style="color: <?= strpos($message, 'succès') !== false ? 'green' : 'red' ?>"><?= $message ?></p>
            <?php endif; ?>

            <form class="publication-form" method="POST" onsubmit="return validateForm()" enctype="multipart/form-data">
                <div>
                    <label>Note :</label>
                    <select name="note" required>
                        <option value="">Choisir une note</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <textarea name="commentaire" placeholder="Partagez votre expérience concernant ce lieu" required></textarea>

                <!-- Champ de téléchargement de fichiers (seules les images sont autorisées) -->
                <label class="file-upload">
                    <i class="fas fa-camera"></i>
                    Ajouter des photos
                    <input type="file" name="media[]" accept="image/*" hidden multiple>
                </label>

                <div class="action-buttons">
                    <button type="reset" class="cancel-btn">Annuler</button>
                    <button type="submit" class="publish-btn">Publier</button>
                </div>
            </form>
        </main>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-container">
            <div class="footer-section about">
                <h2>Dour Tounes</h2>
                <p>
                    Rejoignez notre plateforme et attirez plus de voyageurs en quelques
                    minutes. Partagez votre passion et faites découvrir la Tunisie.
                </p>
            </div>

            <div class="footer-section links">
                <h3>Liens utiles</h3>
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Nos guides</a></li>
                    <li><a href="#">Avis</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <div class="footer-section contact">
                <h3>Contact</h3>
                <p>
                    Email : <a href="mailto:contact@dourtounes.com">contact@dour-tounes.com</a>
                </p>
                <p>Téléphone : +33 1 23 45 67 89</p>
                <p>Adresse : 123, Rue des Voyages, Tunis, Tunis</p>
            </div>

            <div class="footer-section social">
                <h3>Suivez-nous</h3>
                <div class="social-icons">
                    <a href="#"><img src="./images/logos/fb.webp" alt="Facebook" /></a>
                    <a href="#"><img src="./images/logos/mail.png" alt="email" /></a>
                    <a href="#"><img src="./images/logos/insta.jpeg" alt="Instagram" /></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Dour Tounes | Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
