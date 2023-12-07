<?php

include 'Donnees.inc.php';

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';

$utilisateur = "CREATE TABLE Utilisateur (
                login VARCHAR(60) NOT NULL PRIMARY KEY,
                motDePasse VARCHAR(16) NOT NULL,
                nom VARCHAR(60),
                prenom VARCHAR(60),
                dateNaissance DATE,
                mail VARCHAR (60),
                sexe VARCHAR (1),
                adresse VARCHAR (60),
                codePostal INTEGER (5),
                numTelephone VARCHAR (14))";

$cocktail = "CREATE TABLE Cocktail(
             nomCocktail VARCHAR (600) PRIMARY KEY,
             ingredients VARCHAR (2000),
             preparation VARCHAR (2000))";

$aliment = "CREATE TABLE Aliment(
            nomAliment VARCHAR (60) PRIMARY KEY,
            pereAliment VARCHAR (60))";

$panier = "CREATE TABLE Panier(
  loginP VARCHAR (50) PRIMARY KEY,
  nomCocktailP VARCHAR (50),
  dateAjout DATE);
  ALTER TABLE Panier
  ADD Foreign Key (nomCocktailP) 
  REFERENCES Cocktail(nomCocktail)";


$liaison = "CREATE TABLE Liaison(
  nomCocktailU VARCHAR (600),
  nomAlimentU VARCHAR (50),
  PRIMARY KEY (nomAlimentU,nomCocktailU));
  ALTER TABLE Liaison
  ADD Foreign Key (nomCocktailU)
  REFERENCES Cocktail(nomCocktail),
  ADD Foreign Key (nomAlimentU)
  REFERENCES Aliment(nomAliment)
  ";

try {
  $dbco = new PDO("mysql:host=$servname", $user, $pass);
  $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // Cr�ation de la base de donn�es s'elle n'existe pas
    $dbco->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    
    $dbco->exec("USE $dbname"); // S�lectionner la base de donn�es
    
  if ($dbco->query('SHOW TABLES FROM ' . $dbname . ' LIKE "Panier"')->rowCount() > 0)
    $dbco->exec("DROP TABLE Panier;");
  if ($dbco->query('SHOW TABLES FROM ' . $dbname . ' LIKE "Liaison"')->rowCount() > 0)
    $dbco->exec("DROP TABLE Liaison;");
  if ($dbco->query('SHOW TABLES FROM ' . $dbname . ' LIKE "Utilisateur"')->rowCount() > 0)
    $dbco->exec("DROP TABLE Utilisateur;");
  if ($dbco->query('SHOW TABLES FROM ' . $dbname . ' LIKE "Cocktail"')->rowCount() > 0)
    $dbco->exec("DROP TABLE Cocktail;");
  if ($dbco->query('SHOW TABLES FROM ' . $dbname . ' LIKE "Aliment"')->rowCount() > 0)
    $dbco->exec("DROP TABLE Aliment;");

  $dbco->exec($utilisateur);
  $dbco->exec($cocktail);
  $dbco->exec($aliment);
  $dbco->exec($panier);
  $dbco->exec($liaison);
  
  foreach ($Hierarchie as $key => $value) {
    if ($key != 'Aliment') {
      $a = "INSERT INTO Aliment VALUES (\"$key\",\"";
      foreach ($value["super-categorie"] as $key2 => $value2) {
        if ($key2 > 0) {
          $a = $a . "," . $value2;
        } else {
          $a = $a . $value2;
        }
      }
      $a = $a . "\");";
      $dbco->exec($a);
    }
  }

  foreach($Recettes as $key => $value){
    $dbco->exec("INSERT INTO Cocktail VALUES (\"".$value["titre"]."\",\"".$value["ingredients"]."\",\"".str_replace('"', '\"', $value["preparation"])."\");");
    foreach ($value["index"] as $key2 => $value2) {
      
      if (($dbco->query("SELECT * FROM Liaison WHERE NomCocktailU =\"".$value["titre"]."\" AND nomAlimentU=\"".$value2."\";")->rowCount())==0){
        $dbco->exec("INSERT INTO Liaison VALUES (\"".$value["titre"]."\",\"".$value2."\");");
      }
      
    }
  }

} catch (PDOException $e) {

  echo "Erreur : " . $e->getMessage();
}