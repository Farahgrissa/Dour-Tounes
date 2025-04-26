<?php
session_start();
require_once '../config.php'; // adapte le chemin si besoin
$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $error = '';

    // 1. Vérifier s'il s'agit d'un admin
    if ($email === "admin@dourtounes.tn") {
        $admin_password_hash = '$2y$10$D7zZ8b0yQsE8D0mFzQOSneYf7f.V6KoN6eYoWACoix1OSdvazHLEa'; // hash de "admin123"
        if (password_verify($password, $admin_password_hash)) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin/index.php");
            exit();
        } else {
            $error = "Mot de passe incorrect pour l'admin.";
        }
    } else {
        // 2. Vérifier dans la base de données (touriste ou guide)
        $conn = new mysqli("localhost", "root", "", "dour_tounes");
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        // Cherche dans touristes
        $sql = "SELECT * FROM touristes WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['touriste'] = $user;
                header("Location: accueil_touriste.php");
                exit();
            } else {
                $error = "Mot de passe incorrect (touriste).";
            }
        } else {
            // Sinon, cherche dans guides
            $sql = "SELECT * FROM guides WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['guide'] = $user;
                    header("Location: accueil_guide.php");
                    exit();
                } else {
                    $error = "Mot de passe incorrect (guide).";
                }
            } else {
                $error = "Email non trouvé.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
      
/* --- Style général du body --- */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #f8f9fa, #e0e0e0);
    margin: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* --- Container de connexion --- */
.login-container {
    background: #ffffff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    width: 90%;
    max-width: 400px;
    margin: 150px auto 40px auto;
    text-align: center;
}

.login-container h2 {
    margin-bottom: 25px;
    font-size: 28px;
    color: #0d2c54;
}

.login-container form {
    display: flex;
    flex-direction: column;
}

.login-container input {
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.login-container input:focus {
    outline: none;
    border-color: #0d6efd;
}

.login-container button {
    padding: 12px;
    background-color: #0d2c54;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.login-container button:hover {
    background-color: #ff6600;
}

.login-container p {
    font-size: 14px;
    color: #666;
    margin-top: 20px;
}

.login-container a {
    color: #0d6efd;
    font-weight: bold;
    text-decoration: none;
}

.login-container a:hover {
    text-decoration: underline;
}

.error {
    color: red;
    font-size: 14px;
    margin-bottom: 15px;
}

/* --- Footer --- */
footer {
    background-color:rgb(14, 61, 123);
    color: white;
    padding: 40px 20px 20px 20px;
    margin-top: auto;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 1200px;
    margin: auto;
}

.footer-section {
    flex: 1 1 250px;
    margin-bottom: 20px;
}

.footer-section h2, .footer-section h3 {
    color: #ff8c00;
    margin-bottom: 15px;
}

.footer-section p, .footer-section a {
    color: #ccc;
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
    color: #ccc;
    text-decoration: none;
}

.footer-section ul li a:hover {
    color: #ff8c00;
    text-decoration: underline;
}

.social-icons {
    display: flex;
    justify-content: flex-start;
    gap: 10px;
}

.social-icons img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    transition: transform 0.3s;
}

.social-icons img:hover {
    transform: scale(1.1);
}

.footer-bottom {
    text-align: center;
    margin-top: 20px;
    border-top: 1px solid #ff8c00;
    padding-top: 10px;
    font-size: 14px;
}
header {
    display: flex;
    justify-content: center;
    align-items: center; 
    padding: 10px 50px;
    background: rgba(255, 255, 255, 0.9); 
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
}

.logo {
    position: absolute;
    left: 50px; /* mettre le logo à gauche */
}

.logo img {
    width: 70px;
    height: 70px;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 30px;
}

nav ul li {
    display: inline;
}

nav ul li a {
    text-decoration: none;
    color: #004080;
    font-weight: bold;
    font-size: 18px;
}

nav ul li a:hover {
    color: #ff6600;
}

.guide a {
    text-decoration: none;
    background-color: #ff6600;
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
}

/* Section principale */
.hero {
    background:;
    height: 90vh;
    display: flex;
    align-items: center;
    padding: 0 10%;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(5px);
    z-index: 1;
}

.text-content {
    position: relative;
    z-index: 2;
    margin-left: 100px;
}

.text-content h1 {
    font-family: 'Brush Script MT', cursive;
    font-size: 3rem;
    color: #f37a1f;
    font-weight: bold;
    margin: 0;
    max-width: 700px;
}

.text-content p {
    font-size: 1.2rem;
    line-height: 1.6;
    color: #002040;
    max-width: 500px;
    margin-top: 20px;
    text-indent: 40px;
}
</style>

</head>
<body>
<header>
      <div class="logo">
        <img src="./images/logos/logo1.png" alt="Logo Tourisme" />
      </div>
      <nav>
        <ul>
          <li>
            <a href="#accueil">Accueil</a>
          </li>
          <li><a href="#panoramas">destinations</a></li>
          <li><a href="#activites">Activités</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
</header>

<div class="login-container">
    <h2>Connexion</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="email" name="email" placeholder="Adresse e-mail" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>

    <p>Pas encore inscrit ?</p>
    <p><a href="../public/formulaires/form_guide.php">Inscrivez-vous en tant que Guide</a> | <a href="../public/formulaires/form_touriste.php">Inscrivez-vous en tant que touriste</a></p>
</div>
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
          <p>
            Email :
            <a href="mailto:contact@dourtounes.com"
              >contact@dour-tounes.com</a
            >
          </p>
          <p>Téléphone : +33 1 23 45 67 89</p>
          <p>Adresse : 123, Rue des Voyages, Tunis, Tunis</p>
        </div>

        <div class="footer-section social">
          <h3>Suivez-nous</h3>
          <div class="social-icons">
            <a href="#"><img src="./images/logos/fb.webp" alt="Facebook" /></a>
            <a href="#"><img src="./images/logos/mail.png" alt="email" /></a>
            <a href="#"><img src="./images/logos/insta.jpeg" alt="Instagram" /></a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 Dour Tounes | Tous droits réservés.</p>
      </div>
    </footer>

</body>
</html>
