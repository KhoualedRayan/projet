<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Recettes</title>
</head>
<body>
<header>
        <h1>Recettes</h1>
    </header>

    <nav>
        <h2>Rubriques</h2>
        
        <?php 
            echo "<th colspan='2'><a href='#'>Identification</a></th>";  
            echo "</br>";          
            echo "<th colspan='2'><a href='recettes.php'>Toute les recettes</a></th>";            
            echo "</br>";
            echo "<th colspan='2'><a href='#'>Mes recettes préférées</a></th>";
        ?>
    </nav>


    <main>
    
        <?php             
           
        ?>
        
    </main>

    <footer>
        <p> © Ma boutique à moi</p>
    </footer>
</body>
</html>
