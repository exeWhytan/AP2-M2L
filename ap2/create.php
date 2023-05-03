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

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inclure le fichier de configuration de la base de données
    require_once "login.php";

    // Récupérer les données du formulaire
    $nomsalle = $_POST['nomsalle'];
    $idtype = $_POST['idtype'];
    $idetat = 1; // Par défaut, l'état est défini à "Libre"

    // Préparer la requête d'insertion
    $sql = "INSERT INTO salle (nomsalle, idtype, idetat) VALUES (?, ?, ?)";

    // Exécuter la requête avec les valeurs fournies
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nomsalle, $idtype, $idetat]);

    // Rediriger vers la page d'administration après la création de la salle
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Créer une salle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Créer une salle</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="nomsalle">Nom de la salle:</label>
            <input type="text" class="form-control" id="nomsalle" name="nomsalle" required>
        </div>
        <div class="form-group">
            <label for="idtype">Type de salle:</label>
            <select class="form-control" id="idtype" name="idtype" required>
                <!-- Récupérer les types de salle depuis la base de données -->
                <?php
                require_once "login.php";
                $sql = "SELECT * FROM typesalle";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $typesalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($typesalles as $typesalle) {
                    echo "<option value=\"" . $typesalle['idtype'] . "\">" . $typesalle['nomtype'] . "</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
</body>
</html>
