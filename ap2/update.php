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

// Inclure le fichier de configuration de la base de données
require_once "login.php";

// Définir les variables d'erreur et de succès
$error = "";
$success = "";

// Vérifier si l'ID de la salle est spécifié dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Vérifier si le formulaire a été soumis
    if ($_POST) {
        // Récupérer les valeurs du formulaire
        $nomsalle = $_POST['nomsalle'];
        $idtype = $_POST['idtype'];
        $idetat = isset($_POST['idetat']) ? $_POST['idetat'] : 0;

        // Vérifier si la salle existe
        $stmt = $pdo->prepare("SELECT * FROM salle WHERE idsalle = ?");
        $stmt->execute([$id]);
        $salle = $stmt->fetch();

        if ($salle) {
            // Mettre à jour la salle dans la base de données
            $stmt = $pdo->prepare("UPDATE salle SET nomsalle = ?, idtype = ?, idetat = ? WHERE idsalle = ?");
            $stmt->execute([$nomsalle, $idtype, $idetat, $id]);

            $success = "La salle a été modifiée avec succès.";
        } else {
            $error = "La salle spécifiée n'existe pas.";
        }
    } else {
        // Récupérer les informations de la salle depuis la base de données
        $stmt = $pdo->prepare("SELECT * FROM salle WHERE idsalle = ?");
        $stmt->execute([$id]);
        $salle = $stmt->fetch();

        if (!$salle) {
            $error = "La salle spécifiée n'existe pas.";
        }
    }
} else {
    $error = "ID de salle non spécifié.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modifier une salle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Modifier une salle</h2>

        <?php if ($error) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif ($success) : ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php else : ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="nomsalle">Nom de la salle:</label>
                    <input type="text" class="form-control" id="nomsalle" name="nomsalle" value="<?php echo $salle['nomsalle']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="idtype">Type de salle:</label>
                    <select class="form-control" id="idtype" name="idtype" required>
                        <?php
                        // Récupérer tous les types de salle depuis la base de données
                        $stmt = $pdo->query("SELECT * FROM typesalle");
                        $typesalle = $stmt->fetchAll();

                        // Parcourir les types de salle et afficher les options
                        foreach ($typesalle as $type) {
                            $selected = ($type['idtype'] == $salle['idtype']) ? "selected" : "";
                            echo "<option value='" . $type['idtype'] . "' " . $selected . ">" . $type['nomtype'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="idetat">État de la salle:</label>
                    <select class="form-control" id="idetat" name="idetat" required>
                        <?php
                        // Récupérer tous les états de salle depuis la base de données
                        $stmt = $pdo->query("SELECT * FROM etat");
                        $etats = $stmt->fetchAll();

                        // Parcourir les états de salle et afficher les options
                        foreach ($etats as $etat) {
                            $selected = ($etat['idetat'] == $salle['idetat']) ? "selected" : "";
                            echo "<option value='" . $etat['idetat'] . "' " . $selected . ">" . $etat['libelle'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Modifier</button>
                <a href="admin.php" class="btn btn-secondary">Retour</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
