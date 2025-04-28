<?php
// Inclure la configuration pour la connexion à la base de données
require_once('../config.php');

// Vérifier si l'administrateur est connecté
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si non connecté
    exit;
}

// Initialiser les variables
$error = '';
$success = '';

// Gestion de l'ajout d'un administrateur
if (isset($_POST['add_admin'])) {
    // Récupérer les données du formulaire d'ajout
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Sécuriser le mot de passe

    // Insérer le nouvel administrateur dans la base de données
    $query = "INSERT INTO admins (nom, email, password) VALUES ('$nom', '$email', '$password')";
    if ($conn->query($query) === TRUE) {
        $success = 'Administrateur ajouté avec succès.';
    } else {
        $error = 'Erreur lors de l\'ajout de l\'administrateur.';
    }
}

// Gestion de la mise à jour d'un administrateur
if (isset($_POST['update_admin'])) {
    // Récupérer les données du formulaire de modification
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];

    // Mettre à jour les informations de l'administrateur
    $query = "UPDATE admins SET nom = '$nom', email = '$email' WHERE id = '$id'";
    if ($conn->query($query) === TRUE) {
        $success = 'Administrateur mis à jour avec succès.';
    } else {
        $error = 'Erreur lors de la mise à jour de l\'administrateur.';
    }
}

// Gestion de la suppression d'un administrateur
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Supprimer l'administrateur de la base de données
    $query = "DELETE FROM admins WHERE id = '$id'";
    if ($conn->query($query) === TRUE) {
        $success = 'Administrateur supprimé avec succès.';
    } else {
        $error = 'Erreur lors de la suppression de l\'administrateur.';
    }
}

// Récupérer tous les administrateurs depuis la base de données
$query = "SELECT * FROM admins";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Administrateurs</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="dashboard-container">
        <h2>Gérer les Administrateurs</h2>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <!-- Formulaire d'ajout d'un administrateur -->
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
                <?php while ($admin = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $admin['id'] ?></td>
                    <td><?= $admin['nom'] ?></td>
                    <td><?= $admin['email'] ?></td>
                    <td>
                        <a href="gerer_admins.php?edit_id=<?= $admin['id'] ?>" class="button">Modifier</a>
                        <a href="gerer_admins.php?delete_id=<?= $admin['id'] ?>" class="button delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Formulaire de modification d'un administrateur -->
        <?php if (isset($_GET['edit_id'])): ?>
            <?php
            // Récupérer les informations de l'administrateur à modifier
            $edit_id = $_GET['edit_id'];
            $query = "SELECT * FROM admins WHERE id = '$edit_id'";
            $result_edit = $conn->query($query);
            $admin_to_edit = $result_edit->fetch_assoc();
            ?>
            <h3>Modifier un Administrateur</h3>
            <form action="gerer_admins.php" method="POST">
                <input type="hidden" name="id" value="<?= $admin_to_edit['id'] ?>">
                <input type="text" name="nom" placeholder="Nom" value="<?= $admin_to_edit['nom'] ?>" required>
                <input type="email" name="email" placeholder="Adresse email" value="<?= $admin_to_edit['email'] ?>" required>
                <button type="submit" name="update_admin">Mettre à jour</button>
            </form>
        <?php endif; ?>

    </div>

    <?php include('footer.php'); ?>

</body>
</html>
