<?php
require_once '../auth_check.php';

// Suppression guide
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM guides WHERE id = ?")->execute([$_GET['id']]);
    $_SESSION['feedback'] = "Guide supprimé";
    header("Location: index.php");
    exit();
}

// Récupération liste
$guides = $db->query("SELECT * FROM guides ORDER BY nom")->fetchAll();

include '../templates/header.php';
?>

<h2>Gestion des guides</h2>

<a href="form.php" class="btn">Ajouter un guide</a>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Spécialité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($guides as $g): ?>
        <tr>
            <td><?= htmlspecialchars($g['nom']) ?></td>
            <td><?= htmlspecialchars($g['specialite']) ?></td>
            <td>
                <a href="form.php?id=<?= $g['id'] ?>">✏️</a>
                <a href="?delete&id=<?= $g['id'] ?>" onclick="return confirm('Confirmer ?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../templates/footer.php'; ?>