<?php
session_start();

include '../Donnees.inc.php';

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';
session_start();
try {
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
if (isset($_POST['idCocktail'])) {
    $idCocktail = $_POST['idCocktail'];
    if (isset($_SESSION['utilisateur_connecte'])) {

        $nomUtilisateur = $_SESSION['nom_utilisateur'];

        try {
            $stmt = $dbco->prepare("DELETE FROM panier WHERE loginP = :loginP AND id = :id");

            $stmt->bindParam(':loginP', $nomUtilisateur, PDO::PARAM_STR);
            $stmt->bindParam(':id', $idCocktail, PDO::PARAM_STR);

            $stmt->execute();
            echo 'Succès';
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }else{
        echo 'utilisateur non connect.;';
    }
} else {
    echo 'Paramètres manquants';
}
if (isset($_POST['dateCocktail'])) {
    $dateCocktail = $_POST['dateCocktail'];
    if (!isset($_SESSION['utilisateur_connecte'])) {
        foreach ($_SESSION['panier_temporaire'] as $index => $cocktailTemporaire) {
            if ($cocktailTemporaire['dateAjout'] == $dateCocktail) {
                unset($_SESSION['panier_temporaire'][$index]);
                echo 'Cocktail temporaire supprimé avec succès !';
                break; 
            }
        }
    }
}
?>
