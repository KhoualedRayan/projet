<?php
session_start();

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
if (isset($_POST['nomCocktail'])) {
    $nomCocktail = $_POST['nomCocktail'];
    if (isset($_SESSION['utilisateur_connecte'])) {

        $nomUtilisateur = $_SESSION['nom_utilisateur'];
        $dateActuelle = date('Y-m-d H:i:s');

        $stmt = $dbco->prepare("INSERT INTO panier (loginP, nomCocktailP, dateAjout) VALUES (:loginP, :nomCocktailP, :dateAjout)");
        $stmt->bindParam(':loginP', $nomUtilisateur, PDO::PARAM_STR);
        $stmt->bindParam(':nomCocktailP', $nomCocktail, PDO::PARAM_STR);
        $stmt->bindParam(':dateAjout', $dateActuelle, PDO::PARAM_STR);
        try {
            $stmt->execute();
            echo 'Succès';
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    else{
        //Utilisateur pas connecté, stockage temporaire
        //stocker les cocktails temporairement
        if (!isset($_SESSION['panier_temporaire'])) {
            // Si la variable de session pour le panier temporaire n'existe pas, initialisez-la
            $_SESSION['panier_temporaire'] = array();
        }
        $cocktailTemporaire = array(
            'nomCocktail' => $nomCocktail,
            'dateAjout' => date('Y-m-d H:i:s')
        );

        $_SESSION['panier_temporaire'][] = $cocktailTemporaire;

        echo 'Cocktail ajouté au panier temporaire';
    }
} else {
    echo 'Paramètres manquants';
}

?>
