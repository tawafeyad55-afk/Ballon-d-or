<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ballon d'Or 2025</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
require 'connexion_db.php';
?>

<header>
    <h1>⚽ Ballon d'Or 2025</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <?php if (isset($_SESSION['pseudo'])): ?>
            <a href="ajouter_joueur.php">+ Joueur</a>
            <a href="mon_profil.php">Mon profil</a>
            <?php if (!empty($_SESSION['est_admin'])): ?>
                <a href="admin.php">⚙ Admin</a>
            <?php endif; ?>
            <a href="deconnexion.php">Déconnexion (<?= htmlspecialchars($_SESSION['pseudo']) ?>)</a>
        <?php else: ?>
            <a href="inscription.php">Inscription</a>
            <a href="connexion.php">Connexion</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <h2>🏆 Les candidats au Ballon d'Or 2025</h2>

    <?php if (isset($_SESSION['pseudo'])): ?>
        <p style="color:#4caf50;">✅ Connecté en tant que <strong><?= htmlspecialchars($_SESSION['pseudo']) ?></strong> — Clique sur un joueur pour laisser un avis !</p>
    <?php else: ?>
        <p><a href="connexion.php" style="color:#FFD700;">Connecte-toi</a> ou <a href="inscription.php" style="color:#FFD700;">inscris-toi</a> pour voter.</p>
    <?php endif; ?>

    <?php
    // Récupérer les joueurs avec note moyenne
    $stmt = $pdo->query("
        SELECT j.id, j.nom, j.club, j.pays,
               COUNT(a.id) AS nb_avis,
               ROUND(AVG(a.note), 1) AS note_moy
        FROM joueurs j
        LEFT JOIN avis a ON j.id = a.joueur_id
        GROUP BY j.id
        ORDER BY note_moy DESC, nb_avis DESC
    ");
    $joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Drapeaux simples
    $drapeaux = [
        'France' => '🇫🇷', 'Norvège' => '🇳🇴', 'Brésil' => '🇧🇷',
        'Argentine' => '🇦🇷', 'Portugal' => '🇵🇹', 'Espagne' => '🇪🇸',
        'Angleterre' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿', 'Allemagne' => '🇩🇪'
    ];
    ?>

    <div class="grille">
        <?php foreach ($joueurs as $j): ?>
        <a class="carte" href="voir_avis.php?id=<?= $j['id'] ?>">
            <div class="emoji"><?= $drapeaux[$j['pays']] ?? '⚽' ?></div>
            <div class="nom"><?= htmlspecialchars($j['nom']) ?></div>
            <div class="club"><?= htmlspecialchars($j['club']) ?></div>
            <div class="pays"><?= htmlspecialchars($j['pays']) ?></div>
            <div class="note-mini">
                <?php if ($j['nb_avis'] > 0): ?>
                    <div class="etoiles">
                        <?php
                        $n = round($j['note_moy']);
                        echo str_repeat('★', $n) . str_repeat('☆', 5 - $n);
                        ?>
                    </div>
                    <div><?= $j['note_moy'] ?>/5 · <?= $j['nb_avis'] ?> avis</div>
                <?php else: ?>
                    <div style="color:#555;">Pas encore d'avis</div>
                <?php endif; ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</main>

<footer>Ballon d'Or 2025 — Projet Web L1 · HTML · CSS · PHP · SQL</footer>

</body>
</html>
