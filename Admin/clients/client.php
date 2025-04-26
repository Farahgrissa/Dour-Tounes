<?php
require_once '../auth_check.php';

// Suppression client
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM clients WHERE id = ?")->execute([$_GET['id']]);
    $_SESSION['feedback'] = "Client supprimé";
    header("Location: index.php");
    exit();
}

// Récupération clients
$clients = $db->query("SELECT id, nom, email FROM touristes ORDER BY nom")->fetchAll();

include '../template/header.php';
?>

<h2>Gestion des clients</h2>

<table>
    <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($clients as $c): ?>
    <tr>
        <td><?= htmlspecialchars($c['nom']) ?></td>
        <td><?= htmlspecialchars($c['email']) ?></td>
        <td>
            <a href="form.php?id=<?= $c['id'] ?>">✏️</a>
            <a href="avis.php?client_id=<?= $c['id'] ?>">📝 Avis</a>
            <a href="?delete&id=<?= $c['id'] ?>" onclick="return confirm('Confirmer ?')">🗑️</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include '../template/footer.php'; ?>