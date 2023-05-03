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

// Vérifier si le formulaire de réservation a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire
    $date = $_POST['date'];
    $heure_debut = $_POST['heure_debut'];
    $idsalle = $_POST['idsalle'];
    $heure_fin = $_POST['heure_fin'];

    // Vérifier si la salle est disponible pour la réservation
    $stmt = $pdo->prepare("SELECT id_reservation FROM reservation WHERE date = ? AND idsalle = ? AND heure_debut < ? AND heure_fin > ?");
    $stmt->execute([$date, $idsalle, $heure_fin, $heure_debut]);
    $reservationExistante = $stmt->fetch();

    if ($reservationExistante) {
        echo "La salle sélectionnée n'est pas disponible pour cette période.";
        exit();
    }

    // Insérer la réservation dans la base de données
    $stmt = $pdo->prepare("INSERT INTO reservation (date, heure_debut, idsalle, heure_fin, Nom) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$date, $heure_debut, $idsalle, $heure_fin, $_SESSION['login']]);

    // Mettre à jour l'état de la salle
    $stmt = $pdo->prepare("UPDATE salle SET idetat = 2 WHERE idsalle = ?");
    $stmt->execute([$idsalle]);

    header("Location: utilisateur.php");
    exit();
}

// Récupérer les salles disponibles pour la réservation
$stmt = $pdo->query("SELECT * FROM salle WHERE idetat = 1");
$sallesDisponibles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Créer une réservation</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h1>Créer une réservation</h1>
    <a href="logout.php">Déconnexion</a>
    <br><br>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="date">Date :</label>
        <input type="date" id="date" name="date" required>
        <br><br>
        <label for="heure_debut">Heure de début :</label>
        <input type="time" id="heure_debut" name="heure_debut" min="08:00" max="20:00" step="3600" required>
        <br><br>
        <label for="idsalle">Salle :</label>
        <select id="idsalle" name="idsalle" required>
            <?php foreach ($sallesDisponibles as $salle) : ?>
                <option value="<?php echo $salle['idsalle']; ?>"><?php echo $salle['nomsalle']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label for="heure_fin">Heure de fin :</label>
        <input type="time" id="heure_fin" name="heure_fin" min="09:00" max="21:00" step="3600" required>
        <br><br>
        <input type="submit" value="Réserver">
    </form>
  </body>
</html>
