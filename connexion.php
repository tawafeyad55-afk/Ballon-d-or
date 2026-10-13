<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion — Ballon d'Or 2025</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
require 'connexion_db.php';

// Si déjà connecté, rediriger
if (isset($_SESSION['pseudo'])) {
    header('Location: index.php');
    exit;
}

$erreur = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo'] ?? '');
    $mdp    = $_POST['mot_de_passe'] ?? '';

    if (empty($pseudo) || empty($mdp)) {
        $erreur = 'Remplis tous les champs.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            // Connexion réussie
            $_SESSION['utilisateur_id'] = $user['id'];
            $_SESSION['pseudo']         = $user['pseudo'];
            $_SESSION['est_admin']      = $user['est_admin'] ?? 0;
            header('Location: index.php');
            exit;
        } else {
            $erreur = 'Pseudo ou mot de passe incorrect.';
        }
    }
}
?>

<header>
    <h1>⚽ Ballon d'Or 2025</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="inscription.php">Inscription</a>
        <a href="connexion.php">Connexion</a>
    </nav>
</header>

<main>
    <div class="form-box">
        <h2>🔐 Connexion</h2>

        <?php if ($erreur): ?>
            <div class="msg-erreur">❌ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form action="connexion.php" method="POST">

            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" required placeholder="Ton pseudo">

            <label for="mdp">Mot de passe</label>
            <input type="password" id="mdp" name="mot_de_passe" required placeholder="••••••">

            <button type="submit" class="btn">Se connecter</button>
        </form>

        <p style="margin-top:15px; color:#888;">
            Pas encore inscrit ? <a href="inscription.php" style="color:#FFD700;">S'inscrire</a>
        </p>
    </div>
</main>

<footer>Ballon d'Or 2025 — Projet Web L1</footer>

</body>
</html>
