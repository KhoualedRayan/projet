<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../style.css" />
    <script src="../js/inscription.js" defer></script>
    <title>Inscription</title>
</head>
<body>
    <header>
        <h1>Boutique de nourriture MIAM</h1>
    </header>

    <nav>
        <div id="content">
            <h2>Rubriques</h2>
            <ul>
                <li><a href='inscription.php'>Inscription</a></li>
                </br>
                <li><a href='connexion.php'>Connexion</a></li>
                </br>
                <li><a href='recettes.php'>Toute les recettes</a></li>
                </br>
                <li><a href='#'>Mes recettes préférées</a></li>
                </br>
            </ul>
        </div>
    </nav>

    <main>
        <h4>Veuillez remplir le formulaire :</h4>
        <form action="../traitement_php/traitement_inscription.php" method="post">
            <label for="login">Login :</label>
            <input type="text" name="login" required /><br />

            <label for="mdp">Mot de passe :</label>
            <input type="password" name="mdp" required /><br />

            <label for="nom">Nom :</label>
            <input type="text" name="nom" /><br />

            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" /><br />

            <span class="sexe">
                Sexe :
                <input type="radio" name="sexe" value="f" /> Femme
                <input type="radio" name="sexe" value="h" /> Homme
            </span>
            <br />

            <label for="mail">Adresse électronique :</label>
            <input type="email" name="mail" /><br />

            <label for="date_naiss">Date de naissance :</label>
            <input type="date" name="date_naiss" /><br />

            <label for="adresse">Adresse :</label>
            <input type="text" name="adresse" /><br />

            <label for="code_postal">Code postal :</label>
            <input type="number" name="code_postal" /><br />

            <label for="ville">Ville :</label>
            <input type="text" name="ville" /><br />

            <label for="tel">Numéro de téléphone :</label>
            <input type="tel" id="tel" pattern="^0\d{9}$" name="tel" oninput="verifNumeroTel()" />
            <span id="telError" style="color: red;"></span><br /><br />

            <input type="submit" value="Valider" />
        </form>

    </main>

    <footer>
        <p> © Ma boutique à moi</p>
    </footer>
</body>
</html>