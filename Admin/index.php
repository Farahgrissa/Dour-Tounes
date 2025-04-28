<?php
session_start();
require_once '../config.php';

// Vérifie si l'utilisateur est administrateur
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/connexion.php');
    exit();
}

// Si l'administrateur se déconnecte
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../public/connexion.php');
    exit();
}

// Inclure le header
include('header.php');
?>

<section>
    <h2>Bienvenue, Administrateur</h2>
    <p>Vous pouvez gérer les clients et les guides à partir des liens ci-dessus.</p>
</section>

<?php
// Inclure le footer
include('footer.php');
?>
