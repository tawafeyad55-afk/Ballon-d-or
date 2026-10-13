<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Avis — Ballon d'Or 2025</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
require 'connexion_db.php';

// Récupérer l'id du joueur
$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

// Récupérer le joueur
$stmt = $pdo->prepare("SELECT * FROM joueurs WHERE id = ?");
$stmt->execute([$id]);
$joueur = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$joueur) { header('Location: index.php'); exit; }

// Traitement ajout d'avis
$msg = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['utilisateur_id'])) {
    $note       = intval($_POST['note'] ?? -1);
    $commentaire = trim($_POST['commentaire'] ?? '');

    if ($note < 0 || $note > 5) {
        $erreur = 'La note doit être entre 0 et 5.';
    } elseif (empty($commentaire)) {
        $erreur = 'Écris un commentaire.';
    } else {
        $stmt2 = $pdo->prepare("INSERT INTO avis (joueur_id, utilisateur_id, note, commentaire) VALUES (?, ?, ?, ?)");
        $stmt2->execute([$id, $_SESSION['utilisateur_id'], $note, $commentaire]);
        $msg = 'Avis publié !';
    }
}

// Statistiques du joueur
$stmt = $pdo->prepare("SELECT COUNT(*) AS nb, ROUND(AVG(note),1) AS moy FROM avis WHERE joueur_id = ?");
$stmt->execute([$id]);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Tous les avis
$stmt = $pdo->prepare("
    SELECT a.*, u.pseudo
    FROM avis a
    JOIN utilisateurs u ON a.utilisateur_id = u.id
    WHERE a.joueur_id = ?
    ORDER BY a.date_avis DESC
");
$stmt->execute([$id]);
$avis_liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <!-- Fiche du joueur -->
    <div style="background:#1a1400; border:2px solid #FFD700; border-radius:8px; padding:25px; margin-bottom:30px; display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
        <div style="font-size:3.5rem;">⚽</div>
        <div>
            <h2 style="color:#FFD700; margin:0; font-size:1.8rem;"><?= htmlspecialchars($joueur['nom']) ?></h2>
            <p style="margin:5px 0; color:#aaa;">🏟️ <?= htmlspecialchars($joueur['club']) ?> &nbsp;|&nbsp; 🌍 <?= htmlspecialchars($joueur['pays']) ?></p>
        </div>
        <div style="margin-left:auto; text-align:center;">
            <?php if ($stats['nb'] > 0): ?>
                <div style="font-size:2.5rem; font-weight:bold; color:#FFD700;"><?= $stats['moy'] ?>/5</div>
                <div class="etoiles">
                    <?php
                    $n = round($stats['moy']);
                    echo str_repeat('★', $n) . str_repeat('☆', 5 - $n);
                    ?>
                </div>
                <div style="color:#777; font-size:0.85rem;"><?= $stats['nb'] ?> avis</div>
            <?php else: ?>
                <div style="color:#555;">Aucun avis</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Formulaire d'avis -->
    <?php if (isset($_SESSION['utilisateur_id'])): ?>
        <div class="form-box" style="max-width:550px; margin:0 0 35px 0;">
            <h2 style="text-align:left;">✍️ Laisser un avis</h2>

            <?php if ($msg): ?>
                <div class="msg-ok">✅ <?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            <?php if ($erreur): ?>
                <div class="msg-erreur">❌ <?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <form action="voir_avis.php?id=<?= $id ?>" method="POST">

                <label for="note">Note ⭐</label>
                <select id="note" name="note" required>
                    <option value="">-- Choisir --</option>
                    <option value="5">★★★★★ (5/5) — Futur Ballon d'Or !</option>
                    <option value="4">★★★★☆ (4/5) — Excellent</option>
                    <option value="3">★★★☆☆ (3/5) — Très bon</option>
                    <option value="2">★★☆☆☆ (2/5) — Peut mieux faire</option>
                    <option value="1">★☆☆☆☆ (1/5) — Décevant</option>
                    <option value="0">☆☆☆☆☆ (0/5) — Non mérité</option>
                </select>

                <label for="commentaire">Commentaire</label>
                <textarea id="commentaire" name="commentaire" required placeholder="Donne ton avis..."></textarea>

                <button type="submit" class="btn">Publier l'avis</button>
            </form>
        </div>

    <?php else: ?>
        <p style="background:#1e1e1e; border:1px solid #333; border-radius:6px; padding:15px; margin-bottom:30px;">
            👉 <a href="connexion.php" style="color:#FFD700;">Connecte-toi</a> pour laisser un avis.
        </p>
    <?php endif; ?>

    <!-- Liste des avis -->
    <h2>💬 Tous les avis (<?= count($avis_liste) ?>)</h2>

    <?php if (empty($avis_liste)): ?>
        <p style="color:#555;">Aucun avis pour l'instant. Sois le premier !</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis_liste as $a): ?>
                <tr>
                    <td>
                        <a href="profil.php?pseudo=<?= urlencode($a['pseudo']) ?>">
                            <?= htmlspecialchars($a['pseudo']) ?>
                        </a>
                    </td>
                    <td>
                        <span class="etoiles">
                            <?= str_repeat('★', $a['note']) . str_repeat('☆', 5 - $a['note']) ?>
                        </span>
                        <br><?= $a['note'] ?>/5
                    </td>
                    <td><?= nl2br(htmlspecialchars($a['commentaire'])) ?></td>
                    <td style="color:#666; font-size:0.85rem;">
                        <?= date('d/m/Y H:i', strtotime($a['date_avis'])) ?>
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
