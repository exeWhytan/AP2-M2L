<?php
session_start();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion des salles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="ad.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>

<body>
    <img src="images/M2L.png" />
    <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out"></i> Déconnexion</a>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Gestion des salles</h2>
                        <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Ajouter une nouvelle salle</a>
                        <br>
                        <br>
                        <p>Cliquez sur le bouton ci-dessous pour supprimer les réservations antérieures à J-2 :</p>
                        <form action="purge.php" method="post">
                            <button type="submit" name="purge" value="1">Purger</button>
                        </form>
                    </div>
                    <?php
                    // Inclut le fichier de config
                    require_once "login.php";

                    // Connexion à la base de données
                    $link = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

                    // Vérifier la connexion
                    if (!$link) {
                        die("Erreur : Impossible de se connecter.");
                    }

                    // Requête SQL pour récupérer les salles avec leur type
                    $sql = "SELECT s.*, t.nomtype FROM salle s INNER JOIN typesalle t ON t.idtype = s.idtype";

                    $result = $link->query($sql);

                    if ($result->rowCount() > 0)
                    if ($result->rowCount() > 0) {
                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>#</th>";
                        echo "<th>Nom de salle</th>";
                        echo "<th>Type de salle</th>";
                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result->fetch()) {
                            echo "<tr>";
                            echo "<td>" . $row['idsalle'] . "</td>";
                            echo "<td>" . $row['nomsalle'] . "</td>";
                            echo "<td>" . $row['nomtype'] . "</td>";
                            echo "<td>";
                            echo '<a href="update.php?id=' . $row['idsalle'] . '" class="mr-3" title="Modifier" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?id=' . $row['idsalle'] . '" title="Supprimer" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Libérer le résultat de la mémoire
                        $result->closeCursor();
                    } else {
                        echo '<div class="alert alert-danger"><em>Aucun enregistrement trouvé.</em></div>';
                    }
                    
                    // Fermer la connexion à la base de données
                    $link = null;
                    ?>
                    
