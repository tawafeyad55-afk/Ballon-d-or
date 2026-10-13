<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription — Ballon d'Or 2025</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php session_start(); ?>

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
        <h2>📝 Inscription</h2>

        <?php
        // Afficher les erreurs ou succès
        if (isset($_GET['erreur'])) {
            $e = $_GET['erreur'];
            if ($e == 'pseudo_pris')    echo '<div class="msg-erreur">❌ Ce pseudo est déjà utilisé !</div>';
            if ($e == 'email_pris')     echo '<div class="msg-erreur">❌ Cet email est déjà utilisé !</div>';
            if ($e == 'champs_vides')   echo '<div class="msg-erreur">❌ Tous les champs sont obligatoires !</div>';
            if ($e == 'mdp_court')      echo '<div class="msg-erreur">❌ Mot de passe trop court (6 caractères min) !</div>';
        }
        ?>

        <form action="traitement_inscription.php" method="POST">

            <label for="pseudo">Pseudo *</label>
            <input type="text" id="pseudo" name="pseudo" required maxlength="50"
                   placeholder="Choisis un pseudo"
                   value="<?= htmlspecialchars($_GET['pseudo'] ?? '') ?>">

            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required
                   placeholder="ton@email.fr"
                   value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">

            <label for="mdp">Mot de passe *</label>
            <input type="password" id="mdp" name="mot_de_passe" required placeholder="Minimum 6 caractères">

            <button type="submit" class="btn">S'inscrire</button>
        </form>

        <p style="margin-top:15px; color:#888;">
            Déjà inscrit ? <a href="connexion.php" style="color:#FFD700;">Se connecter</a>
        </p>
    </div>
</main>

<footer>Ballon d'Or 2025 — Projet Web L1</footer>

</body>
</html>
