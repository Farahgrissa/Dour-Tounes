<?php
session_start();
require_once '../config.php';

// Vérifie si l'utilisateur est administrateur
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/connexion.php');
    exit();
}
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit(); 
}
include('header.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <section>
        <h2>Bienvenue, Administrateur</h2>
        <p>Vous pouvez gérer les clients et les guides à partir des liens ci-dessus.</p>
    </section>

<?php
include('footer.php');
?>
</body>
</html>
