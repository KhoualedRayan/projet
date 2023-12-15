<?php
include '../Donnees.inc.php';

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';

try {
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $mdp = $_POST["mdp"];

    // Requête préparée pour récupérer le compte correspondant au login
    try {
        $stmt = $dbco->prepare("SELECT * FROM utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur de requête : " . $e->getMessage();
    }
    if ($user) {
        echo "Mot de passe saisi : $mdp<br>";

        echo "Mot de passe haché dans la base de données : " . $user['motDePasse'] ;
        echo "<br/>";

    }


    if ($user && password_verify($mdp, $user['motDePasse'])) {
        // Connectez l'utilisateur ici (vous pouvez implémenter votre logique de connexion)
        echo "Connexion réussie !";
    } else {
        echo "Identifiants invalides. Veuillez réessayer.";
    }
}
?>
