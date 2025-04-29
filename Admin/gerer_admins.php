<?php
// Inclure la configuration pour la connexion à la base de données
require_once('../config.php');

// Vérifier si l'administrateur est connecté
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Initialiser les variables
$error = '';
$success = '';

// Ajout d'un administrateur
if (isset($_POST['add_admin'])) {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    if ($email && $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO admins (nom, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$nom, $email, $hashedPassword])) {
            $success = 'Administrateur ajouté avec succès.';
        } else {
            $error = 'Erreur SQL : ' . implode(", ", $stmt->errorInfo());
        }
    } else {
        $error = 'Données invalides.';
    }
}

// Mise à jour d'un administrateur
if (isset($_POST['update_admin'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE admins SET nom = ?, email = ? WHERE id = ?");
    if ($stmt->execute([$nom, $email, $id])) {
        $success = 'Administrateur mis à jour avec succès.';
    } else {
        $error = 'Erreur lors de la mise à jour.';
    }
}

// Suppression d'un administrateur
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = 'Administrateur supprimé avec succès.';
    } else {
        $error = 'Erreur lors de la suppression.';
    }
}

// Récupérer tous les administrateurs
$stmt = $pdo->query("SELECT * FROM admins");
$admins = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Administrateurs</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <h2>Gérer les Administrateurs</h2>

        <!-- Messages -->
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
        <h3>Ajouter un Administrateur</h3>
        <form action="gerer_admins.php" method="POST">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" name="add_admin">Ajouter</button>
        </form>

        <!-- Table des administrateurs -->
        <h3>Liste des Administrateurs</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= htmlspecialchars($admin['id']) ?></td>
                        <td><?= htmlspecialchars($admin['nom']) ?></td>
                        <td><?= htmlspecialchars($admin['email']) ?></td>
                        <td>
                            <a href="gerer_admins.php?edit_id=<?= $admin['id'] ?>" class="button">Modifier</a>
                            <a href="gerer_admins.php?delete_id=<?= $admin['id'] ?>" class="button delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulaire de modification -->
        <?php if (isset($_GET['edit_id'])): ?>
            <?php
            $edit_id = $_GET['edit_id'];
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
            $stmt->execute([$edit_id]);
            $admin_to_edit = $stmt->fetch();
            ?>
            <h3>Modifier un Administrateur</h3>
            <form action="gerer_admins.php" method="POST">
                <input type="hidden" name="id" value="<?= $admin_to_edit['id'] ?>">
                <input type="text" name="nom" value="<?= htmlspecialchars($admin_to_edit['nom']) ?>" required>
                <input type="email" name="email" value="<?= htmlspecialchars($admin_to_edit['email']) ?>" required>
                <button type="submit" name="update_admin">Mettre à jour</button>
            </form>
        <?php endif; ?>
    </div>

    <?php include('footer.php'); ?>

</body>
</html>
