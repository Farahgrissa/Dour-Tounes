<?php

require_once '../config.php';

if (!isset($_GET['client_id'])) {
    die('Le paramètre client_id est manquant.');
}

$client_id = (int)$_GET['client_id'];

// Suppression avis
if (isset($_POST['delete_avis'])) {
    $db->prepare("DELETE FROM avis WHERE id = ?")->execute([$_POST['avis_id']]);
    $_SESSION['feedback'] = "Avis supprimé";
}

$avis = $db->prepare("
    SELECT * 
    FROM avis
    WHERE client_id = ?
")->execute([$client_id])->fetchAll();

$client = $db->prepare("SELECT nom FROM clients WHERE id = ?")->execute([$client_id])->fetch();

include 'header.php';
?>

<h2>Avis de <?= htmlspecialchars($client['nom']) ?></h2>

<table>
    <tr>
        <th>Note</th>
        <th>Commentaire</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php foreach ($avis as $a): ?>
    <tr>
        <td><?= str_repeat('★', $a['note']) ?></td>
        <td><?= htmlspecialchars($a['commentaire']) ?></td>
        <td><?= $a['date_creation'] ?></td>
        <td>
            <form method="post" onsubmit="return confirm('Supprimer cet avis ?')">
                <input type="hidden" name="avis_id" value="<?= $a['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button type="submit" name="delete_avis">🗑️</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'footer.php'; ?>
