<?php
require_once '../auth_check.php';

$client_id = (int)$_GET['client_id'];

// Suppression avis
if (isset($_POST['delete_avis'])) {
    $db->prepare("DELETE FROM avis WHERE id = ?")->execute([$_POST['avis_id']]);
    $_SESSION['feedback'] = "Avis supprimé";
}

// Récupération des avis du client
$avis = $db->prepare("
    SELECT a.*, d.nom AS destination 
    FROM avis a
    LEFT JOIN destinations d ON a.destination_id = d.id
    WHERE a.client_id = ?
")->execute([$client_id])->fetchAll();

// Récupération infos client
$client = $db->prepare("SELECT nom FROM clients WHERE id = ?")->execute([$client_id])->fetch();

include '../templates/header.php';
?>

<h2>Avis de <?= htmlspecialchars($client['nom']) ?></h2>

<table>
    <tr>
        <th>Destination</th>
        <th>Note</th>
        <th>Commentaire</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php foreach ($avis as $a): ?>
    <tr>
        <td><?= htmlspecialchars($a['destination']) ?></td>
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

<?php include '../templates/footer.php'; ?>