<?php
require_once '../config.php';
session_start();

// Gestion suppression d'un avis
if (isset($_POST['delete_avis'])) {
    if (isset($_POST['avis_id'])) {
        $stmt = $pdo->prepare("DELETE FROM avis WHERE id = ?");
        $stmt->execute([$_POST['avis_id']]);
        $_SESSION['feedback'] = "Avis supprimé avec succès.";
    }
}

// Vérifier si un client_id est fourni
if (isset($_GET['client_id'])) {
    $client_id = (int)$_GET['client_id'];

    // Récupérer les avis spécifiques à ce client
    $stmt = $pdo->prepare("SELECT * FROM avis WHERE client_id = ?");
    $stmt->execute([$client_id]);
    $avis = $stmt->fetchAll();

    // Récupérer les informations du client
    $stmt = $pdo->prepare("SELECT nom FROM clients WHERE id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch();

    $client_name = $client ? htmlspecialchars($client['nom']) : "Client inconnu";
} else {
    // Récupérer tous les avis si aucun client_id n'est spécifié
    $stmt = $pdo->query("SELECT * FROM avis");
    $avis = $stmt->fetchAll();
    $client_name = "Tous les clients";
}

include 'header.php';
?>

<link rel="stylesheet" href="admin.css">
<h2>Avis de <?= $client_name ?></h2>

<?php if (isset($_SESSION['feedback'])): ?>
    <p class="success"><?= $_SESSION['feedback'] ?></p>
    <?php unset($_SESSION['feedback']); ?>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Note</th>
        <th>Commentaire</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php foreach ($avis as $a): ?>
    <tr>
        <td><?= str_repeat('★', (int)$a['note']) ?></td>
        <td><?= htmlspecialchars($a['commentaire']) ?></td>
        <td><?= $a['date'] ?? '-' ?></td>
        <td>
            <form method="post" onsubmit="return confirm('Supprimer cet avis ?');">
                <input type="hidden" name="avis_id" value="<?= $a['id'] ?>">
                <button type="submit" name="delete_avis">🗑️ Supprimer</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'footer.php'; ?>
