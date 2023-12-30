<?php
include '../Donnees.inc.php';
session_start();

$nomUtilisateur = $_SESSION['nom_utilisateur'];
$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';

// Connexion à la base de données
try {
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_POST["deco"])) {
        deconnexion();
    }
    if (isset($_POST["deco"])) {
        supprCompte($dbco, $nomUtilisateur);
    }
    modifProfil($dbco);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

function deconnexion()
{
    // Détruit toutes les variables de session
    $_SESSION = array();

    //efface également les cookie de session.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Finalement, détruit la session
    session_destroy();

    exit();
}
function supprCompte($dbco, $nomUtilisateur)
{
    try {
        // Requête SQL pour supprimer l'utilisateur avec le login spécifié
        $query = "DELETE FROM utilisateur WHERE login = :login";
        $stmt = $dbco->prepare($query);
        $stmt->bindParam(':login', $nomUtilisateur, PDO::PARAM_STR);
        $stmt->execute();

        // Détruit toutes les variables de session
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        exit();

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

}
function modifProfil($dbco)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $login = $_POST["login"];
        $mdp = $_POST["mdp"];
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $sexe = isset($_POST["sexe"]) ? $_POST["sexe"] : null;
        $mail = $_POST["mail"];
        $date_naiss = isset($_POST["date_naiss"]) ? $_POST["date_naiss"] : null;
        $adresse = $_POST["adresse"];
        $code_postal = $_POST["code_postal"];
        $ville = $_POST["ville"];
        $tel = $_POST["tel"];


        $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);
        $stmtCheck = $dbco->prepare("SELECT * FROM utilisateur WHERE login = ?");
        $stmtCheck->bindParam(1, $login);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            if(!empty($mdp))
               $stmtUpdate = $dbco->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, sexe = ?, mail = ?, dateNaissance = ?, adresse = ?, codePostal = ?, ville = ?, numTelephone = ?,motDePasse = ? WHERE login = ?");
            else
               $stmtUpdate = $dbco->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, sexe = ?, mail = ?, dateNaissance = ?, adresse = ?, codePostal = ?, ville = ?, numTelephone = ? WHERE login = ?");

            $stmtUpdate->bindParam(1, $nom);
            $stmtUpdate->bindParam(2, $prenom);
            $stmtUpdate->bindParam(3, $sexe);
            $stmtUpdate->bindParam(4, $mail);
            $stmtUpdate->bindParam(5, $date_naiss);
            $stmtUpdate->bindParam(6, $adresse);
            $stmtUpdate->bindParam(7, $code_postal);
            $stmtUpdate->bindParam(8, $ville);
            $stmtUpdate->bindParam(9, $tel);
            if (!empty($mdp)){
                $stmtUpdate->bindParam(10, $hashedPassword);
                $stmtUpdate->bindParam(11, $login);
            }else{
                $stmtUpdate->bindParam(10, $login);
            }


            // Exécute la mise à jour
            if ($stmtUpdate->execute())
                reussiteModification();
            else
                echecModification();

        }

    }
}
function echecModification()
{
    echo "Erreur lors de la modifcation du profil. Redirection vers la page de profil...";
    //header("Location: ../php/profil.php");
    exit();
}
function reussiteModification()
{
    echo "Modification du profil réussi ! Redirection vers la page de la page de profil.";
    header("Location: ../php/profil.php");
    exit();
}
?>
