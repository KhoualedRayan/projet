<?php
include 'Donnees.inc.php';

$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';
session_start();
try {
    $dbco = new PDO("mysql:host=$servname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbco->exec("USE $dbname"); // S�lectionner la base de donn�es
} catch (PDOException $e) {

    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/index.js" defer></script>
    <title>Acceuil</title>
</head>
<body>
    <header>
        <h1>Acceuil</h1>
        <div id="compte">
            <?php
            if (isset($_SESSION['utilisateur_connecte'])) {
                // Récupérer le nom de l'utilisateur depuis la session
                $nomUtilisateur = $_SESSION['nom_utilisateur'];

                // Afficher le nom de l'utilisateur dans le HEADER
                echo 'Bienvenue, ' . $nomUtilisateur . '!';
            }
            ?>
        </div>
    </header>

    <nav>
        <div id="content">
            <h2>Rubriques</h2>
            <ul>
                <li><a href='index.php'>Accueil</a></li>
                <br />
                <li><a href='php/inscription.php'>Inscription</a></li>
                <br>
                    <li><a href='php/connexion.php'>Connexion</a></li>
                </br>
                <?php
                if (isset($_SESSION['utilisateur_connecte'])) {
                    echo "<li><a href='php/profil.php'>Profil</a></li>";
                    echo '<br/>';
                }
                ?>
                <li><a href='php/recettes.php'>Toutes les recettes</a></li>
                <br>
                    <li><a href='php/panier.php'>Panier</a></li>
                </br>
            </ul>
        </div>
    </nav>

    <main>
        <h2>Tous les Cocktails</h2>

        <form>
            <label for="searchTerm">Recherche d'aliments :</label>
            <input type="text" id="searchTerm" name="searchTerm" oninput="performSearch()" />
        </form>

        <select id="resultsDropdown" size="20">
            <!-- Options de la liste déroulante -->
        </select>


        <?php
        $alimentsInclusArray = array();
        $alimentsExclusArray = array();
        ?>
        <table border="1" id="tab-aliments" class="tab-image">
            <tr class="tab-image-ligne"><th>Aliments inclus</th><th></th><th></th><th>Aliments exclus</th></tr>
            <?php
            ajouterMot("Fruit", $alimentsInclusArray);
            ajouterMot("Melon", $alimentsInclusArray);
            ajouterMot("Pastèque", $alimentsInclusArray);
            ?>


        </table>
        <script>
            var alimentsInclusArray = <?php echo json_encode($alimentsInclusArray); ?>;
            var alimentsExclusArray = <?php echo json_encode($alimentsExclusArray); ?>;
        </script>
        <div id="tableaux-info">
            <!-- Le contenu des tableaux sera affiché ici -->
        </div>
        <div id="cocktails-container">
            <!-- Le contenu des cocktails sera affiché ici -->
        </div>

    </main>

    <script>

    function afficherTableauCoteClient(response) {
    // Effacer le tableau et le reconstruire avec les données mises à jour
    $('#tableaux-info').empty();

    // Utiliser la réponse JSON pour construire le tableau avec les nouvelles données
    for (var i = 0; i < response.length; i++) {
        $('#tableaux-info').append('<tr><td>' + response[i] + '</td></tr>');
    }
}

function ajouterUnMot(aliment, aliments_inclus) {
    // Effectuer une requête AJAX pour appeler la fonction PHP qui ajoute le mot
    $.ajax({
        type: 'POST',
        url: 'traitement_php/ajouterMot.php',
        data: { mot: aliment, alimentsInclusArray: aliments_inclus },
        success: function(response) {
            // La fonction PHP a été appelée avec succès, la réponse est dans 'response'
            console.log(response);

            // Mettre à jour la liste côté client
            afficherTableauCoteClient(response);
        },
        error: function(error) {
            // Une erreur s'est produite lors de l'appel de la fonction PHP
            console.error("Erreur lors de l'appel de la fonction PHP :", error);
        }
    });
}







    function initializePage() {
        $('#resultsDropdown').hide();
        $('#searchTerm').on('input', performSearch);

        // Ajouter un gestionnaire d'événements au clic pour les options de la liste déroulante
        $('#resultsDropdown').on('click', 'option', function () {
            // Récupérer la valeur de l'option cliquée
            var selectedAliment = $(this).val();
            ajouterUnMot(selectedAliment, alimentsInclusArray);

        });
    }

    function performSearch() {
        var searchTerm = $('#searchTerm').val();

        if (searchTerm.trim() === '') {
            $('#resultsDropdown').hide();
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'traitement_php/traitement_recherche.php',
            data: { searchTerm: searchTerm },
            success: function (data) {
                $('#resultsDropdown').html(data);
                $('#resultsDropdown').show();
            },
            error: function () {
                console.log('Erreur lors de la requête.');
            }
        });
    }

    $(document).ready(initializePage);
</script>





    <footer>
        <p> ©Ma boutique à moi</p>
    </footer>
</body>
</html>

<?php
function ajouterMot($mot, &$alimentsInclusArray)
{
    // Rajouter $mot au tableau approprié
    $alimentsInclusArray[] = $mot;

    // Afficher la ligne du tableau
    echo '<tr>';
    echo '<td>' . $mot . '</td>';
    echo '<td class="tab-image-filtre"><img src="images/supprimer.png" alt="Supprimer" onclick="supprimerLigne(this)"></td>';
    echo '<td class="tab-image-filtre"><img src="images/changer-colonne.png" alt="ChangerColonne" onclick="echangerColonne(this)"></td>';
    echo '<td></td>';
    echo '</tr>';
}

?>