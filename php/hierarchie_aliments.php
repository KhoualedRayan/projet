<?php
$servname = 'localhost';
$dbname = 'MaBase';
$user = 'root';
$pass = '';

session_start();

// Initialiser la session 'fil' comme un tableau si ce n'est pas déjà le cas
if (!isset($_SESSION['fil']) || !is_array($_SESSION['fil'])) {
    $_SESSION['fil'] = array();
}

try {
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mot = isset($_POST['mot']) ? $_POST['mot'] : 'Aliment';

    // Si un mot spécifique est passé en paramètre, le fil d'Ariane est reconstruit à partir de ce mot
    if ($mot != 'Aliment') {
        $_SESSION['fil'] = array();
        $currentMot = $mot;
        while ($currentMot != 'Aliment') {
            $_SESSION['fil'][] = $currentMot;

            $queryPereAliment = "SELECT pereAliment FROM Aliment WHERE nomAliment = :currentMot";
            $stmtPereAliment = $dbco->prepare($queryPereAliment);
            $stmtPereAliment->bindParam(':currentMot', $currentMot);
            $stmtPereAliment->execute();

            if ($rowPereAliment = $stmtPereAliment->fetch(PDO::FETCH_ASSOC)) {
                $currentMot = $rowPereAliment['pereAliment'];
            } else {
                break;  // Sortir de la boucle si le père n'est pas trouvé
            }
        }
        $_SESSION['fil'][] = 'Aliment';
        $_SESSION['fil'] = array_reverse($_SESSION['fil']);
    }

    // Afficher le fil d'Ariane avec des liens cliquables
    echo "<p id='fil_ariane'>Fil d'Ariane : ";
    foreach ($_SESSION['fil'] as $i => $motAriane) {
        if ($i > 0) {
            echo " -> ";
        }
        echo "<a href='#' class='mot-cliquable' data-index='$i'>$motAriane</a>";
    }
    echo "</p>";

    // Récupérer les sous-aliments actuels
    $motSousAliments = $mot;
    $querySousAliments = "SELECT nomAliment FROM Aliment WHERE pereAliment = :motSousAliments";
    $stmtSousAliments = $dbco->prepare($querySousAliments);
    $stmtSousAliments->bindParam(':motSousAliments', $motSousAliments);
    $stmtSousAliments->execute();

    if ($stmtSousAliments->rowCount() > 0) {
        echo "<ul id='zone_sous_aliments'>";
        while ($rowSousAliments = $stmtSousAliments->fetch(PDO::FETCH_ASSOC)) {
            echo "<li class='mot-cliquable'>$rowSousAliments[nomAliment]</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun sous-aliment trouvé pour $mot.</p>";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var filAriane = document.getElementById('fil_ariane');
        filAriane.addEventListener('click', function (event) {
            event.preventDefault();
            var target = event.target;
            if (target.classList.contains('mot-cliquable')) {
                var index = target.getAttribute('data-index');
                chargerSousAliments(index);
            }
        });

        function chargerSousAliments(index) {
            // Mettre à jour le fil d'Ariane sur la page
            fetch('votre_script_php_pour_les_sous_aliments.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'mot=' + encodeURIComponent(document.querySelector('.mot-cliquable[data-index="' + index + '"]').innerHTML),
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('zone_sous_aliments').innerHTML = data;

                // Si le mot cliqué est "Aliment", le fil d'Ariane est vidé
                if (document.querySelector('.mot-cliquable[data-index="' + index + '"]').innerHTML === 'Aliment') {
                    document.getElementById('fil_ariane').innerHTML = "";
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des sous-aliments:', error));
        }
    });
</script>
