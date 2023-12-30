<?php
include '../Donnees.inc.php';

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';

// Connexion � la base de donn�es
try {
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Traitement du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    // Verif login unique
    $verifLogin = $dbco->prepare("SELECT COUNT(*) FROM utilisateur WHERE login = ?");
    $verifLogin->bindParam(1, $login);
    $verifLogin->execute();
    $count = $verifLogin->fetchColumn();
    //login existe d�j�
    if ($count > 0) {
        echecInscription();
    }
    //login unique
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




    // Validation des donn�es
    if (empty($login) || empty($mdp)) {
        echo "Veuillez remplir les champs obligatoires du formulaire.";
    } else {
        // Hasher le mot de passe
        $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

        // Pr�pare la requ�te pour ins�rer le compte dans la base de donn�es
        $stmt = $dbco->prepare("INSERT INTO utilisateur (login, motDePasse, nom, prenom, sexe, mail, dateNaissance, adresse, codePostal, ville, numTelephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $login);
        $stmt->bindParam(2, $hashedPassword);
        $stmt->bindParam(3, $nom);
        $stmt->bindParam(4, $prenom);
        $stmt->bindParam(5, $sexe);
        $stmt->bindParam(6, $mail);
        $stmt->bindParam(7, $date_naiss);
        $stmt->bindParam(8, $adresse);
        $stmt->bindParam(9, $code_postal);
        $stmt->bindParam(10, $ville);
        $stmt->bindParam(11, $tel);

        // Ex�cuter la requ�te
        if ($stmt->execute()) {
            reussiteInscription();
        } else {
            echecInscription();
        }
    }
}
function echecInscription(){
    echo "Erreur lors de l'inscription. Redirection vers la page d'inscription...";
    header("Location: ../php/inscription.php");
    exit();
}function reussiteInscription(){
    echo "Inscription r�ussi ! Redirection vers la page de l'index.";
    header("Location: ../index.php");
    exit();
}

?>
