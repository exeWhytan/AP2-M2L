<?php
// Vérifier si le paramètre "id" est présent dans l'URL
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Inclure le fichier de configuration
    require_once "login.php";

    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

    // Préparer une instruction de suppression
    $sql = "DELETE FROM salle WHERE idsalle = :id";

    // Préparer la requête
    $stmt = $pdo->prepare($sql);

    // Récupérer la valeur du paramètre id depuis l'URL
    $param_id = trim($_GET["id"]);

    // Liaison des variables à l'instruction préparée en tant que paramètres
    $stmt->bindParam(':id', $param_id, PDO::PARAM_INT);

    // Exécution de la requête préparée
    if ($stmt->execute()) {
        // Rediriger vers la page admin.php après la suppression
        header("location: admin.php");
        exit();
    } else {
        echo "Erreur : Impossible d'exécuter la requête.";
    }
} else {
    echo "Erreur : ID de salle non spécifié.";
}
?>
