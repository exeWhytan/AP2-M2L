<?php
session_start();
error_reporting(0);

// Vérifier si l'utilisateur est connecté en vérifiant les informations de session
if (!isset($_SESSION['login']) || !isset($_SESSION['typerole'])) {
    echo "Vous devez vous connecter pour accéder à cette page.";
    exit();
}

// Vérifier si l'utilisateur a le rôle "admin"
if ($_SESSION['typerole'] !== "admin") {
    echo "Accès non autorisé. Cette page est réservée aux administrateurs.";
    exit();
}

require_once 'login.php'; // Fichier de configuration de la base de données

/*************************PHASE DE PURGE DES RÉSERVATIONS*******************************/

// Calculer la date actuelle
$aujourdhui = date("Y-m-d");

// Calculer la date dans 2 jours
$dateLimite = date('Y-m-d', strtotime($aujourdhui. ' + 2 days'));

// Requête pour supprimer les réservations de moins de 2 jours
$stmtDelete = $pdo->prepare("DELETE FROM reservation WHERE date < ?");
$stmtDelete->execute([$dateLimite]);

// Requête pour mettre à jour l'état des salles occupées à libre
$stmtUpdate = $pdo->prepare("UPDATE salle SET idetat = 1 WHERE idetat = 2");
$stmtUpdate->execute();

echo "Les réservations de moins de 2 jours ont été purgées et l'état des salles a été mis à jour.";

?>

<button onclick="window.location.href = 'admin.php';">Retour vers admin</button>
