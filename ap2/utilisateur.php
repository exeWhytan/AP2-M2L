<?php
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant les informations de session
if (!isset($_SESSION['login']) || !isset($_SESSION['typerole'])) {
    echo "Vous devez vous connecter pour accéder à cette page.";
    exit();
}

// Vérifier si l'utilisateur a le rôle "utilisateur"
if ($_SESSION['typerole'] !== "utilisateur") {
    echo "Accès non autorisé. Cette page est réservée aux utilisateurs.";
    exit();
}

// Inclure le fichier de configuration de la base de données
require_once 'login.php';

// Récupérer les réservations de l'utilisateur connecté triées par date décroissante
$login = $_SESSION['login'];
$stmt = $pdo->prepare("SELECT r.*, s.nomsalle, ts.nomtype FROM reservation r INNER JOIN salle s ON r.idsalle = s.idsalle INNER JOIN typesalle ts ON s.idtype = ts.idtype WHERE r.Nom = ? ORDER BY r.date ASC");
$stmt->execute([$login]);
$reservations = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Espace utilisateur</title>
    <link rel="stylesheet" href="utilisateur.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        td.libre {
            background-color: #b3ffb3;
        }
        
        td.occupee {
            background-color: #ff9999;
        }
    </style>
</head>
<body>
    <h1>Bienvenue sur votre espace utilisateur, <?php echo $_SESSION['login']; ?> !</h1>
    <a href="logout.php">Déconnexion</a>
    <br><br>
    <p>Vous pouvez consulter le planning de réservation en cliquant sur le bouton ci-dessous :</p>
    <a href="planning.php"><button>Consulter le planning des réservations</button></a>
    <br><br>
    <p>Vous pouvez créer une réservation en cliquant sur le bouton ci-dessous :</p>
    <a href="createReservation.php"><button>Créer une réservation</button></a>
    <br><br>
    <h2>Vos réservations :</h2>
    <?php if (count($reservations) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Heure de début</th>
                <th>Heure de fin</th>
                <th>Nom de la salle</th>
                <th>Type de salle</th>
            </tr>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo $reservation['id_reservation']; ?></td>
                    <td><?php echo $reservation['date']; ?></td>
                    <td><?php echo $reservation['heure_debut']; ?></td>
                    <td><?php echo $reservation['heure_fin']; ?></td>
                    <td><?php echo $reservation['nomsalle']; ?></td>
                    <td><?php echo $reservation['nomtype']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune réservation trouvée.</p>
    <?php endif; ?>
</body>
</html>

