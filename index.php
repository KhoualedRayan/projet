<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Index</title>
</head>
<body>
    <header>
        <h1>Boutique de nourriture MIAM</h1>
    </header>

    <nav>
        <h2>Rubriques</h2>
        
        <?php             
            echo "<th colspan='2'><a href='inscription.php'>Inscription</a></th>";  
            echo "</br>"; 
            echo "<th colspan='2'><a href='connexion.php'>Connexion</a></th>";  
            echo "</br>";          
            echo "<th colspan='2'><a href='recettes.php'>Toute les recettes</a></th>";            
            echo "</br>";
            echo "<th colspan='2'><a href='#'>Mes recettes préférées</a></th>";
            
        ?>
    </nav>

    <main>
        <h4>Veuillez remplir le formulaire :</h4>
        <form action="traitement_identification.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br/>
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required><br/>
        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required><br/>
        <label for="adresse">Code postal :</label>
        <input type="text" name="code_postal" required><br/>
        <label for="adresse">Ville :</label>
        <input type="text" name="ville" required><br/><br/>
        <input type="submit" value="Valider">
        </form>
        
    </main>

    <footer>
        <p> © Ma boutique à moi</p>
    </footer>
</body>
</html>