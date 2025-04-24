<?php
require_once '../auth_check.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Nettoyage des données
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $specialite = htmlspecialchars($_POST['specialite'] ?? '');
    
    // Validation
    if (empty($nom)) $errors[] = "Le nom est obligatoire";
    if (strlen($specialite) < 3) $errors[] = "Spécialité trop courte";
    
    // Si pas d'erreurs
    if (empty($errors)) {
        try {
            // UPDATE ou INSERT selon l'ID
            if (!empty($_POST['id'])) {
                $stmt = $db->prepare("UPDATE guides SET nom=?, specialite=? WHERE id=?");
                $stmt->execute([$nom, $specialite, $_POST['id']]);
            } else {
                $stmt = $db->prepare("INSERT INTO guides (nom, specialite) VALUES (?, ?)");
                $stmt->execute([$nom, $specialite]);
            }
            
            $_SESSION['feedback'] = "Guide enregistré avec succès";
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Erreur base de données : " . $e->getMessage();
        }
    }
}

// Pré-remplissage si édition
$guide = ['nom' => '', 'specialite' => ''];
if (!empty($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM guides WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $guide = $stmt->fetch() ?: $guide;
}

include '../templates/header.php';
?>

<!-- Formulaire HTML -->
<form method="post">
    <input type="hidden" name="id" value="<?= $guide['id'] ?? '' ?>">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    
    <div class="form-group">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= $guide['nom'] ?>" required>
    </div>
    
    <div class="form-group">
        <label>Spécialité :</label>
        <input type="text" name="specialite" value="<?= $guide['specialite'] ?>">
    </div>
    
    <button type="submit">Enregistrer</button>
</form>

<!-- Affichage des erreurs -->
<?php if (!empty($errors)) include '../templates/form_feedback.php'; ?>

<?php include '../templates/footer.php'; ?>