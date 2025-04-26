<?php
// Démarre la session pour vérifier les accès
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si l'utilisateur est connecté (sinon redirection vers la page de login)
require_once __DIR__ . '/../auth_check.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">a 
    <title>Administration - Dour Tounes</title>
    <link rel="stylesheet" href="../CSS/admin.css">
</head>
<body>

<header class="admin-header">
    <div class="container">
        <h1>Panneau d'administration</h1>
        <nav class="admin-nav">
            <ul>
                <li><a href="/admin/index.php">Accueil</a></li>
                <li><a href="/admin/guides/index.php">Guides</a></li>
                <li><a href="/admin/clients/index.php">Clients</a></li>
                <li><a href="/logout.php">🔓 Déconnexion</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="admin-main">
