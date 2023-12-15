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

    $stmt = $dbco->prepare('SELECT * FROM utilisateur WHERE login = :login');
    $stmt->bindValue('login', $login);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Mot de passe saisi : $mdp<br>";
        $passwordHash = $user['motDePasse'];
        echo "Mot de passe haché dans la base de données : " . $passwordHash;
        echo "<br/>";

        if (password_verify($mdp, $user['motDePasse'])) {
            echo "Connexion réussi ! Redirection vers la page de l'index.";
            header("Location: ../index.php");
            exit();
        } else {
            echo "Erreur lors de la connexion. Redirection vers la page de connexion...";
            header("Location: ../php/connexion.php");
            exit();
        }
    } else {
        echo "Utilisateur non trouvé.";
        header("Location: ../php/connexion.php");
        exit();
    }




}
?>
