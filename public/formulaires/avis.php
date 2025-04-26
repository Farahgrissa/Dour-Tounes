<?php
session_start();
require_once '../config.php'; // fichier de connexion à la base

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header('Location: ../connexion.php');
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_SESSION['client_id'];
    $note = intval($_POST['note'] ?? 0);
    $commentaire = trim($_POST['commentaire']);

    if ($note >= 1 && $note <= 10 && !empty($commentaire)) {
        $stmt = $pdo->prepare("INSERT INTO avis (client_id, note, commentaire) VALUES (?, ?, ?)");
        if ($stmt->execute([$client_id, $note, $commentaire])) {
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
</head>
<body>
<div class="publication-container">
    <header class="publication-header">
        <h1>Dour Tounes</h1>
        <div class="user-profile">
            <span class="username"><?= htmlspecialchars($_SESSION['nom_utilisateur'] ?? 'Utilisateur') ?></span>
            <i class="fas fa-user-circle"></i>
        </div>
    </header>

    <main class="publication-content">
        <?php if (!empty($message)): ?>
            <p style="color: <?= strpos($message, 'succès') ? 'green' : 'red' ?>"><?= $message ?></p>
        <?php endif; ?>

        <form class="publication-form" method="POST">
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

            <!-- Ajout de fichiers (non enregistré dans DB ici) -->
            <label class="file-upload">
                <i class="fas fa-camera"></i>
                Ajouter des photos et vidéos
                <input type="file" hidden multiple>
            </label>

            <!-- Boutons d'action -->
            <div class="action-buttons">
                <button type="reset" class="cancel-btn">Annuler</button>
                <button type="submit" class="publish-btn">Publier</button>
            </div>
        </form>
    </main>
</div>
</body>
</html>


<script>let currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= 2000; year--) {
                       document.write(`<option value="${year}">${year}</option>`);
                                }
    
    // Exemple de dynamisation du nom d'utilisateur
    document.querySelector('.username').textContent = "Utilisateur Connecté";</script>
</body>
</html>
