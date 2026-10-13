<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Ballon d'Or 2025</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
require 'connexion_db.php';

// Seulement l'admin peut accéder
if (empty($_SESSION['est_admin'])) {
    header('Location: index.php');
    exit;
}

$msg = '';

// Supprimer un joueur
if (isset($_GET['supprimer_joueur'])) {
    $j_id = intval($_GET['supprimer_joueur']);
    $stmt = $pdo->prepare("DELETE FROM joueurs WHERE id = ?");
    $stmt->execute([$j_id]);
    $msg = 'Joueur supprimé.';
}

// Supprimer un avis
if (isset($_GET['supprimer_avis'])) {
    $a_id = intval($_GET['supprimer_avis']);
    $stmt = $pdo->prepare("DELETE FROM avis WHERE id = ?");
    $stmt->execute([$a_id]);
    $msg = 'Avis supprimé.';
}

// Récupérer tous les joueurs
$joueurs = $pdo->query("SELECT * FROM joueurs ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les avis
$avis_liste = $pdo->query("
    SELECT a.*, u.pseudo, j.nom AS nom_joueur
    FROM avis a
    JOIN utilisateurs u ON a.utilisateur_id = u.id
    JOIN joueurs j ON a.joueur_id = j.id
    ORDER BY a.date_avis DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<header>
    <h1>⚽ Ballon d'Or 2025</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="admin.php">⚙ Admin</a>
        <a href="deconnexion.php">Déconnexion (<?= htmlspecialchars($_SESSION['pseudo']) ?>)</a>
    </nav>
</header>

<main>
    <h2>⚙️ Panneau d'administration</h2>

    <?php if ($msg): ?>
        <div class="msg-ok">✅ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Gestion des joueurs -->
    <div style="background:#1e1e1e; border:1px solid #333; border-radius:8px; padding:20px; margin-bottom:30px;">
        <h3 style="color:#e53935; margin-bottom:15px;">🗑️ Supprimer un joueur</h3>
        <table>
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Club</th>
                    <th>Pays</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $j): ?>
                <tr>
                    <td><?= htmlspecialchars($j['nom']) ?></td>
                    <td><?= htmlspecialchars($j['club']) ?></td>
                    <td><?= htmlspecialchars($j['pays']) ?></td>
                    <td>
                        <a href="admin.php?supprimer_joueur=<?= $j['id'] ?>"
                           class="btn btn-rouge"
                           onclick="return confirm('Supprimer <?= htmlspecialchars($j['nom']) ?> et tous ses avis ?')">
                           Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Gestion des avis -->
    <div style="background:#1e1e1e; border:1px solid #333; border-radius:8px; padding:20px;">
        <h3 style="color:#e53935; margin-bottom:15px;">🗑️ Supprimer un avis</h3>
        <?php if (empty($avis_liste)): ?>
            <p style="color:#555;">Aucun avis.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Joueur</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis_liste as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['pseudo']) ?></td>
                    <td><?= htmlspecialchars($a['nom_joueur']) ?></td>
                    <td>
                        <span class="etoiles">
                            <?= str_repeat('★', $a['note']) . str_repeat('☆', 5 - $a['note']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars(substr($a['commentaire'], 0, 60)) ?>...</td>
                    <td>
                        <a href="admin.php?supprimer_avis=<?= $a['id'] ?>"
                           class="btn btn-rouge"
                           onclick="return confirm('Supprimer cet avis ?')">
                           Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</main>

<footer>Ballon d'Or 2025 — Projet Web L1</footer>

</body>
</html>
