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
            session_start();
            // Stocker des informations de connexion dans la session
            $_SESSION['utilisateur_connecte'] = true;
            $_SESSION['nom_utilisateur'] = $login;
            // Compléter le panier avec le panier temporaire
            completerPanier($dbco, $login);

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
function completerPanier($dbco,$login){
    if (isset($_SESSION['panier_temporaire']) && !empty($_SESSION['panier_temporaire'])) {
        $panierTemporaire = $_SESSION['panier_temporaire'];
        foreach ($panierTemporaire as $cocktailTemporaire) {
            $stmt = $dbco->prepare("INSERT INTO panier (loginP, nomCocktailP, dateAjout) VALUES (:loginP, :nomCocktailP, :dateAjout)");
            $stmt->bindParam(':loginP', $login, PDO::PARAM_STR);
            $stmt->bindParam(':nomCocktailP', $cocktailTemporaire['nomCocktail'], PDO::PARAM_STR);
            $stmt->bindParam(':dateAjout', $cocktailTemporaire['dateAjout'], PDO::PARAM_STR);
            try {
                $stmt->execute();
                echo 'Succès';
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        }
        $_SESSION['panier_temporaire'] = array();
    }
}
?>
