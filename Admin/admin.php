<?php
// 1. Sécurité et initialisation
session_start();
require_once '../config.php';

// Vérification de l'authentification admin
if(!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php?error=admin_only');
    exit();
}

// 2. Traitement des actions (CRUD)
$action = $_GET['action'] ?? 'dashboard';
$section = $_GET['section'] ?? 'dashboard';

// 3. Header HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tunisie Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 250px; background: #2c3e50; padding: 20px 0; }
        .admin-main { flex: 1; padding: 20px; background: #f5f6fa; }
        .nav-link { color: #ecf0f1; margin-bottom: 5px; }
        .nav-link:hover { background: #34495e; }
        .active-section { background: #3498db!important; }
        .table-responsive { background: white; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <div class="text-center mb-4">
                <h4 class="text-white">Tunisie Travel</h4>
                <small class="text-muted">Panel Admin</small>
            </div>
            <nav class="nav flex-column px-3">
                <a href="?section=dashboard" class="nav-link <?= $section === 'dashboard' ? 'active-section' : '' ?>">
                    Tableau de bord
                </a>
                <a href="?section=guides" class="nav-link <?= $section === 'guides' ? 'active-section' : '' ?>">
                    Gestion Guides
                </a>
                <a href="?section=clients" class="nav-link <?= $section === 'clients' ? 'active-section' : '' ?>">
                    Gestion Clients
                </a>
                <a href="?section=avis" class="nav-link <?= $section === 'avis' ? 'active-section' : '' ?>">
                    Avis Clients
                </a>
                <a href="deconnexion.php" class="nav-link text-warning mt-4">
                    Déconnexion
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <?php
            // 4. Contenu dynamique par section
            switch($section) {
                case 'guides':
                    // SECTION GUIDES
                    $guides = $pdo->query("SELECT * FROM guides ORDER BY date_inscription DESC")->fetchAll();
                    ?>
                    <h2>Gestion des guides</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Inscrit le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($guides as $guide): ?>
                                <tr>
                                    <td><?= htmlspecialchars($guide['id']) ?></td>
                                    <td><?= htmlspecialchars($guide['nom']) ?></td>
                                    <td><?= htmlspecialchars($guide['email']) ?></td>
                                    <td><?= htmlspecialchars($guide['telephone']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($guide['date_inscription'])) ?></td>
                                    <td>
                                        <a href="?section=guides&action=edit&id=<?= $guide['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                        <a href="?section=guides&action=delete&id=<?= $guide['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    break;

                case 'clients':
                    // SECTION CLIENTS
                    $clients = $pdo->query("SELECT * FROM clients ORDER BY date_inscription DESC")->fetchAll();
                    ?>
                    <h2>Gestion des clients</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Inscrit le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($clients as $client): ?>
                                <tr>
                                    <td><?= htmlspecialchars($client['id']) ?></td>
                                    <td><?= htmlspecialchars($client['nom']) ?></td>
                                    <td><?= htmlspecialchars($client['prenom']) ?></td>
                                    <td><?= htmlspecialchars($client['email']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($client['date_inscription'])) ?></td>
                                    <td>
                                        <a href="?section=clients&action=voir&id=<?= $client['id'] ?>" class="btn btn-sm btn-info">Voir</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    break;

                case 'avis':
                    // SECTION AVIS
                    $avis = $pdo->query("
                        SELECT a.*, c.nom, c.prenom 
                        FROM avis a
                        JOIN clients c ON a.client_id = c.id
                        ORDER BY a.date_publication DESC
                    ")->fetchAll();
                    ?>
                    <h2>Avis des clients</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Note</th>
                                    <th>Commentaire</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($avis as $avi): ?>
                                <tr>
                                    <td><?= htmlspecialchars($avi['prenom'].' '.$avi['nom']) ?></td>
                                    <td><?= str_repeat('★', $avi['note']) . str_repeat('☆', 5 - $avi['note']) ?></td>
                                    <td><?= htmlspecialchars(substr($avi['commentaire'], 0, 50)) ?>...</td>
                                    <td><?= date('d/m/Y', strtotime($avi['date_publication'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $avi['approuve'] ? 'success' : 'warning' ?>">
                                            <?= $avi['approuve'] ? 'Approuvé' : 'En attente' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="?section=avis&action=approuver&id=<?= $avi['id'] ?>" class="btn btn-sm btn-success">Approuver</a>
                                        <a href="?section=avis&action=supprimer&id=<?= $avi['id'] ?>" class="btn btn-sm btn-danger">Supprimer</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    break;

                default:
                    // DASHBOARD PAR DÉFAUT
                    $stats = [
                        'guides' => $pdo->query("SELECT COUNT(*) FROM guides")->fetchColumn(),
                        'clients' => $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn(),
                        'avis' => $pdo->query("SELECT COUNT(*) FROM avis")->fetchColumn()
                    ];
                    ?>
                    <h2>Tableau de bord</h2>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $stats['guides'] ?></h5>
                                    <p class="card-text">Guides enregistrés</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $stats['clients'] ?></h5>
                                    <p class="card-text">Clients inscrits</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-info">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $stats['avis'] ?></h5>
                                    <p class="card-text">Avis clients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
            }
            ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Confirmation pour les actions sensibles
        document.querySelectorAll('.btn-danger').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if(!confirm('Cette action est irréversible. Confirmer ?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>