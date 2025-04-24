<?php
session_start();
require_once '../config.php'; // adapte le chemin si besoin

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
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .login-container p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .login-container a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Connexion</h2>
    <form action="connexion.php" method="POST">
        <input type="text" name="email" placeholder="Adresse e-mail" required>
        <input type="password" name="motdepasse" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    <p>Pas encore inscrit ? <a href="role.html">Inscrivez-vous</a></p>
</div>

</body>
</html>
