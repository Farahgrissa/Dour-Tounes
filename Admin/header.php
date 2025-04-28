<?php
// Vérifier si l'utilisateur est connecté en tant qu'administrateur
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../public/connexion.php');
    exit();
}
?>
<!-- header.php -->
<header>
    <div class="header-container">
        <div class="logo">
            <a href="index.php">Dour Tounes - Admin</a>
        </div>
        <nav class="admin-nav">
            <ul>
                <li><a href="index.php">Tableau de bord</a></li>
                <li><a href="gerer_admins.php">Gérer les Administrateurs</a></li>
                <li><a href="gerer_guides.php">Gérer les guides</a></li> 
                <li><a href="gerer_clients.php">Gérer les Clients</a></li> 
                <li><a href="avis.php">Gérer les Avis</a></li>
                <li><a href="deconexion.php">Se déconnecter</a></li>
            </ul>
        </nav>
    </div>
</header>
