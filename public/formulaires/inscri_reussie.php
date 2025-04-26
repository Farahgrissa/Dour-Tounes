<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription réussie</title>
    <meta http-equiv="refresh" content="5;url=../../index.php">
    <link rel="stylesheet" href="../css/formulairetourist.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #2980b9;
        }
        footer {
    background-color: #144a82;
    color: white;
    padding: 40px 20px;
    text-align: center;
    margin-top: 100px;
}
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 50px;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: fixed;
    width :95%;
    z-index: 100;
    background: transparent ;
}

.logo img {
    width: 70px;
    height: 70px;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 20px;
}

nav ul li {
    display: inline;
}

nav ul li a {
    text-decoration: none;
    color: #004080;
    font-weight: bold;
    font-size: 16px;
}

nav ul li a:hover {
    color: #ff6600;
}

.footer-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: auto;
}

.footer-section {
    flex: 1;
    min-width: 250px;
    margin-bottom: 20px;
}

.footer-section h2, .footer-section h3 {
    color: #ff8c00;
    margin-bottom: 10px;
}

.footer-section p, .footer-section a {
    color: #ddd;
    text-decoration: none;
    font-size: 14px;
}

.footer-section a:hover {
    color: #ff8c00;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin: 8px 0;
}

.footer-section ul li a {
    color: white;
    text-decoration: none;
}

.footer-section ul li a:hover {
    text-decoration: underline;
    color: #ff8c00;
}

.social-icons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.social-icons img {
    width: 30px;
    height: 30px;
    transition: transform 0.3s;
}

.social-icons img:hover {
    transform: scale(1.1);
}

.footer-bottom {
    margin-top: 20px;
    border-top: 1px solid #ff8c00;
    padding-top: 10px;
    font-size: 14px;
}




    </style>
</head>
<body>

    <!-- Header statique -->
    <header>
      <div class="logo">
        <img src="../../images/logos/logo1.png" alt="Logo Tourisme" />
      </div>
      <nav>
        <ul>
          <li><a href="../../index.php#accueil">Accueil</a></li>
          <li><a href="../../index.php#panoramas">Destinations</a></li>
          <li><a href="../../index.php#activites">Activités</a></li>
          <li><a href="../../index.php#contact">Contact</a></li>
        </ul>
      </nav>
    </header>

    <!-- Contenu principal -->
    <div class="container">
        <h2>Inscription réussie !</h2>
        <p>Merci pour votre inscription. Vous pouvez maintenant vous connecter à votre compte.</p>
        <p>Redirection vers l'accueil dans quelques secondes...</p>
        <a href="../../index.php" class="button">Retour à l'accueil</a>
    </div>

    <!-- Footer statique -->
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
            <li><a href="#">À propos</a></li>
            <li><a href="#">Nos guides</a></li>
            <li><a href="#">Avis</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </div>

        <div class="footer-section contact">
          <h3>Contact</h3>
          <p>Email : <a href="mailto:contact@dourtounes.com">contact@dour-tounes.com</a></p>
          <p>Téléphone : +33 1 23 45 67 89</p>
          <p>Adresse : 123, Rue des Voyages, Tunis, Tunis</p>
        </div>

        <div class="footer-section social">
          <h3>Suivez-nous</h3>
          <div class="social-icons">
            <a href="#"><img src="../../images/logos/fb.webp" alt="Facebook" /></a>
            <a href="#"><img src="../../images/logos/mail.png" alt="email" /></a>
            <a href="#"><img src="../../images/logos/insta.jpeg" alt="Instagram" /></a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 Dour Tounes | Tous droits réservés.</p>
      </div>
    </footer>

</body>
</html>
