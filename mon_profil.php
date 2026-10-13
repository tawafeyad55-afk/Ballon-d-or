<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil — Ballon d'Or 2025</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header('Location: connexion.php');
    exit;
}
// Rediriger vers la page profil publique
header('Location: profil.php?pseudo=' . urlencode($_SESSION['pseudo']));
exit;
?>

</body>
</html>
