<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un joueur — Ballon d'Or 2025</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
require 'connexion_db.php';

// Faut être connecté
if (!isset($_SESSION['pseudo'])) {
    header('Location: connexion.php');
    exit;
}

$msg = '';
$erreur = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom  = trim($_POST['nom'] ?? '');
    $club = trim($_POST['club'] ?? '');
    $pays = trim($_POST['pays'] ?? '');

    if (empty($nom) || empty($club) || empty($pays)) {
        $erreur = 'Tous les champs sont obligatoires.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO joueurs (nom, club, pays) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $club, $pays]);
        $msg = 'Joueur ajouté avec succès !';
    }
}
?>

<header>
    <h1>⚽ Ballon d'Or 2025</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="ajouter_joueur.php">+ Joueur</a>
        <a href="mon_profil.php">Mon profil</a>
        <?php if (!empty($_SESSION['est_admin'])): ?>
            <a href="admin.php">⚙ Admin</a>
        <?php endif; ?>
        <a href="deconnexion.php">Déconnexion (<?= htmlspecialchars($_SESSION['pseudo']) ?>)</a>
    </nav>
</header>

<main>
    <div class="form-box">
        <h2>➕ Ajouter un joueur</h2>

        <?php if ($msg): ?>
            <div class="msg-ok">✅ <?= htmlspecialchars($msg) ?> <a href="index.php" style="color:#4caf50;">Voir l'accueil</a></div>
        <?php endif; ?>
        <?php if ($erreur): ?>
            <div class="msg-erreur">❌ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form action="ajouter_joueur.php" method="POST">

            <label for="nom">Nom du joueur *</label>
            <input type="text" id="nom" name="nom" required placeholder="Ex: Lionel Messi">

            <label for="club">Club *</label>
            <input type="text" id="club" name="club" required placeholder="Ex: FC Barcelone">

            <label for="pays">Nationalité *</label>
            <input type="text" id="pays" name="pays" required placeholder="Ex: Argentine">

            <button type="submit" class="btn">Ajouter</button>
        </form>

        <br>
        <a href="index.php" style="color:#FFD700;">← Retour à l'accueil</a>
    </div>
</main>

<footer>Ballon d'Or 2025 — Projet Web L1</footer>

</body>
</html>
