<?php
session_start();
require 'connexion_db.php';

// Sécurité : refuser si pas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inscription.php');
    exit;
}

// Récupérer les données
$pseudo = trim($_POST['pseudo'] ?? '');
$email  = trim($_POST['email'] ?? '');
$mdp    = $_POST['mot_de_passe'] ?? '';

// Vérifier champs vides
if (empty($pseudo) || empty($email) || empty($mdp)) {
    header('Location: inscription.php?erreur=champs_vides');
    exit;
}

// Vérifier longueur mot de passe
if (strlen($mdp) < 6) {
    header('Location: inscription.php?erreur=mdp_court&pseudo=' . urlencode($pseudo) . '&email=' . urlencode($email));
    exit;
}

// Vérifier pseudo déjà pris
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo = ?");
$stmt->execute([$pseudo]);
if ($stmt->fetch()) {
    header('Location: inscription.php?erreur=pseudo_pris&email=' . urlencode($email));
    exit;
}

// Vérifier email déjà utilisé
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    header('Location: inscription.php?erreur=email_pris&pseudo=' . urlencode($pseudo));
    exit;
}

// Sauvegarder dans la base
$mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, email, mot_de_passe) VALUES (?, ?, ?)");
$stmt->execute([$pseudo, $email, $mdp_hache]);

// Connecter automatiquement
$_SESSION['utilisateur_id'] = $pdo->lastInsertId();
$_SESSION['pseudo']         = $pseudo;
$_SESSION['est_admin']      = 0;

// Aller à l'accueil
header('Location: index.php');
exit;
?>
