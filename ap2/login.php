<?php
session_start();

/*************************CONNEXION A LA BDD***************************************/

error_reporting(E_ALL);
ini_set("display_errors", 1);

$host = 'localhost';
$db   = 'm2l2ap2';
$user = 'root';
$pass = 'root';
$dsn = "mysql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (\PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
    exit();
}

/*************************PHASE DE CONNEXION*********************************************/

if ($_POST) {
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];

    $stmt = $pdo->prepare("SELECT u.login, r.typerole FROM users u INNER JOIN role r ON u.idrole = r.idrole WHERE u.login = ? AND u.mdp = ?");
    $stmt->execute([$login, $mdp]);
    $user = $stmt->fetch();

    if (!$user) {
        echo "Authentification refusée";
    } else {
        echo "Authentification acceptée";

        $_SESSION['login'] = $user['login'];
        $_SESSION['typerole'] = $user['typerole'];

        if ($user['typerole'] == "admin") {
            header("Location: admin.php");
            exit();
        } elseif ($user['typerole'] == "utilisateur") {
            header("Location: utilisateur.php");
            exit();
        } else {
            echo "Erreur: Rôle non reconnu";
        }
    }
}
?>
