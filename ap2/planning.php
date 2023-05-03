<?php
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant les informations de session
if (!isset($_SESSION['login']) || !isset($_SESSION['typerole'])) {
    echo "Vous devez vous connecter pour accéder à cette page.";
    exit();
}

require_once 'login.php'; // Fichier de configuration de la base de données

// Vérifier si l'utilisateur a le rôle "utilisateur"
if ($_SESSION['typerole'] !== "utilisateur") {
    echo "Accès non autorisé. Cette page est réservée aux utilisateurs.";
    exit();
}


// Récupérer la liste des salles
$stmt = $pdo->query("SELECT * FROM salle");
$salles = $stmt->fetchAll();

// Récupérer la date actuelle
$dateActuelle = date('Y-m-d');

// Générer les dates des 7 prochains jours
$dates = array();
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('Y-m-d', strtotime($dateActuelle . ' + ' . $i . ' days'));
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Planning des salles</title>
    <link rel="stylesheet" href="planning.css">
  </head>
  <body>
    <h1>Planning des salles</h1>
    <a href="logout.php">Déconnexion</a>
    <br><br>

    <table>
      <thead>
        <tr>
          <th>Salle</th>
          <?php foreach ($dates as $date) : ?>
            <th><?php echo $date; ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($salles as $salle) : ?>
          <tr>
            <td><?php echo $salle['nomsalle']; ?></td>
            <?php foreach ($dates as $date) : ?>
              <td>
                <table>
                  <thead>
                    <tr>
                      <th>Heure</th>
                      <th>Disponibilité</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $stmt = $pdo->prepare("SELECT * FROM reservation WHERE date = ? AND idsalle = ?");
                      $stmt->execute([$date, $salle['idsalle']]);
                      $reservations = $stmt->fetchAll();

                      $plageHoraires = array();
                      $heureDebut = strtotime('08:00');
                      $heureFin = strtotime('20:00');

                      while ($heureDebut < $heureFin) {
                          $plageHoraires[] = date('H:i', $heureDebut);
                          $heureDebut = strtotime('+1 hour', $heureDebut);
                      }

                      foreach ($plageHoraires as $plageHoraire) {
                          $occupee = false;

                          foreach ($reservations as $reservation) {
                              $heureDebutReservation = strtotime($reservation['heure_debut']);
                              $heureFinReservation = strtotime($reservation['heure_fin']);

                              if ($plageHoraire >= date('H:i', $heureDebutReservation) && $plageHoraire < date('H:i', $heureFinReservation)) {
                                  $occupee = true;
                                  break;
                              }
                          }

                          echo '<tr>';
                          echo '<td>' . $plageHoraire . '</td>';

                          if ($occupee) {
                              echo '<td class="occupee">Occupée</td>';
                          } else {
                              echo '<td class="libre">Libre</td>';
                          }

                          echo '</tr>';
                      }
                    ?>
                  </tbody>
                </table>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <button onclick="window.location.href = 'utilisateur.php';">Retour vers utilisateur</button>
  </body>
</html>
