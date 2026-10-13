<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil — Ballon d'Or 2025</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
require 'connexion_db.php';

$pseudo = $_GET['pseudo'] ?? '';
if (empty($pseudo)) { header('Location: index.php'); exit; }

// Récupérer l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = ?");
$stmt->execute([$pseudo]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) { header('Location: index.php'); exit; }

// Récupérer ses avis
$stmt = $pdo->prepare("
    SELECT a.*, j.nom AS nom_joueur, j.club
    FROM avis a
    JOIN joueurs j ON a.joueur_id = j.id
    WHERE a.utilisateur_id = ?
    ORDER BY a.date_avis DESC
");
$stmt->execute([$user['id']]);
$avis_user = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<header>
    <h1>⚽ Ballon d'Or 2025</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <?php if (isset($_SESSION['pseudo'])): ?>
            <a href="mon_profil.php">Mon profil</a>
            <?php if (!empty($_SESSION['est_admin'])): ?>
                <a href="admin.php">⚙ Admin</a>
            <?php endif; ?>
            <a href="deconnexion.php">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php">Connexion</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <h2>👤 Profil de <?= htmlspecialchars($pseudo) ?></h2>
    <p style="color:#888;">Membre depuis le <?= date('d/m/Y', strtotime($user['date_inscription'])) ?> · <?= count($avis_user) ?> avis publiés</p>

    <?php if (empty($avis_user)): ?>
        <p style="color:#555; margin-top:20px;"><?= htmlspecialchars($pseudo) ?> n'a pas encore d'avis.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Club</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis_user as $a): ?>
                <tr>
                    <td>
                        <a href="voir_avis.php?id=<?= $a['joueur_id'] ?>">
                            <?= htmlspecialchars($a['nom_joueur']) ?>
                        </a>
                    </td>
                    <td style="color:#888;"><?= htmlspecialchars($a['club']) ?></td>
                    <td>
                        <span class="etoiles">
                            <?= str_repeat('★', $a['note']) . str_repeat('☆', 5 - $a['note']) ?>
                        </span>
                    </td>
                    <td><?= nl2br(htmlspecialchars($a['commentaire'])) ?></td>
                    <td style="color:#666; font-size:0.85rem;">
                        <?= date('d/m/Y', strtotime($a['date_avis'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="index.php" class="btn">← Retour à l'accueil</a>
</main>

<footer>Ballon d'Or 2025 — Projet Web L1</footer>

</body>
</html>
