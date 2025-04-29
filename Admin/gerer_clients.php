<?php
session_start();
require_once '../config.php';

// Vérifie si l'utilisateur est administrateur
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/connexion.php');
    exit();
}

$message = '';

// Ajout ou modification d'un client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter_modifier_client'])) {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        // Hachage du mot de passe
        $password_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

        // Si l'ID est défini, on modifie un client
        if (isset($_POST['client_id']) && $_POST['client_id'] !== '') {
            $client_id = $_POST['client_id'];
            $stmt = $pdo->prepare("UPDATE clients SET nom = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$nom, $email, $password_hash, $client_id]);
            $message = "Client modifié avec succès.";
        } else {
            // Ajouter un nouveau client
            $stmt = $pdo->prepare("INSERT INTO clients (nom, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $email, $password_hash]);
            $message = "Client ajouté avec succès.";
        }
    }

    // Supprimer un client
    if (isset($_POST['supprimer_client'])) {
        $client_id = $_POST['client_id'];
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
        $stmt->execute([$client_id]);
        $message = "Client supprimé avec succès.";
    }
}

// Récupérer tous les clients
$stmt_clients = $pdo->query("SELECT * FROM clients");
$clients = $stmt_clients->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Gestion des Clients</h1>
        <a href="index.php">Retour au Tableau de Bord</a>
    </header>

    <section>
        <?php if (!empty($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <h2>Liste des Clients</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td>
                            <a href="?edit=<?= $client['id'] ?>">Modifier</a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
                                <button type="submit" name="supprimer_client">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3><?= isset($_GET['edit']) ? 'Modifier' : 'Ajouter' ?> un Client</h3>
        <form method="POST">
            <?php
            $client_edit = null;
            if (isset($_GET['edit'])) {
                $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
                $stmt->execute([$_GET['edit']]);
                $client_edit = $stmt->fetch();
            }
            ?>
            <?php if ($client_edit): ?>
                <input type="hidden" name="client_id" value="<?= $client_edit['id'] ?>">
            <?php endif; ?>

            <input type="text" name="nom" placeholder="Nom" value="<?= $client_edit['nom'] ?? '' ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= $client_edit['email'] ?? '' ?>" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>

            <button type="submit" name="ajouter_modifier_client">
                <?= isset($_GET['edit']) ? 'Mettre à jour' : 'Ajouter' ?> le Client
            </button>
        </form>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
