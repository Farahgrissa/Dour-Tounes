<?php
session_start();
require_once '../config.php';

// Vérifie si l'utilisateur est administrateur
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/connexion.php');
    exit();
}

$message = '';

// Ajout ou modification d'un guide
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter_modifier_guide'])) {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $ville = $_POST['ville'];
        $password = $_POST['password'];  
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        if (!empty($_POST['guide_id'])) {
            $guide_id = $_POST['guide_id'];
            $stmt = $pdo->prepare("UPDATE guides SET nom = ?, email = ?, telephone = ?, ville = ?, password = ? WHERE id = ?");
            $stmt->execute([$nom, $email, $telephone, $ville, $password_hash, $guide_id]);
            $message = "Guide modifié avec succès.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO guides (nom, email, telephone, ville, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $email, $telephone, $ville, $password_hash]);
            $message = "Guide ajouté avec succès.";
        }
    }

    if (isset($_POST['supprimer_guide'])) {
        $guide_id = $_POST['guide_id'];
        $stmt = $pdo->prepare("DELETE FROM guides WHERE id = ?");
        $stmt->execute([$guide_id]);
        $message = "Guide supprimé avec succès.";
    }
}

// Récupérer tous les guides
$stmt_guides = $pdo->query("SELECT * FROM guides");
$guides = $stmt_guides->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Guides</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Gestion des Guides</h1>
        <a href="index.php">Retour au Tableau de Bord</a>
    </header>

    <section>
        <?php if (!empty($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <h2>Liste des Guides</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Ville</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($guides as $guide): ?>
                    <tr>
                        <td><?= htmlspecialchars($guide['nom']) ?></td>
                        <td><?= htmlspecialchars($guide['email']) ?></td>
                        <td><?= htmlspecialchars($guide['telephone']) ?></td>
                        <td><?= htmlspecialchars($guide['ville']) ?></td>
                        <td>
                            <a href="?edit=<?= $guide['id'] ?>">Modifier</a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="guide_id" value="<?= $guide['id'] ?>">
                                <button type="submit" name="supprimer_guide">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3><?= isset($_GET['edit']) ? 'Modifier' : 'Ajouter' ?> un Guide</h3>
        <form method="POST">
            <?php
            $guide_edit = null;
            if (isset($_GET['edit'])) {
                $stmt = $pdo->prepare("SELECT * FROM guides WHERE id = ?");
                $stmt->execute([$_GET['edit']]);
                $guide_edit = $stmt->fetch();
            }
            ?>
            <?php if ($guide_edit): ?>
                <input type="hidden" name="guide_id" value="<?= $guide_edit['id'] ?>">
            <?php endif; ?>

            <input type="text" name="nom" placeholder="Nom" value="<?= $guide_edit['nom'] ?? '' ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= $guide_edit['email'] ?? '' ?>" required>
            <input type="text" name="telephone" placeholder="Téléphone" value="<?= $guide_edit['telephone'] ?? '' ?>" required>
            <input type="text" name="ville" placeholder="Ville" value="<?= $guide_edit['ville'] ?? '' ?>" required>
            <input type="password" name="password" placeholder="Mot de passe" required>

            <button type="submit" name="ajouter_modifier_guide">
                <?= isset($_GET['edit']) ? 'Mettre à jour' : 'Ajouter' ?> le Guide
            </button>
        </form>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
