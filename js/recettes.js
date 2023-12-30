$(document).ready(function () {
    // Fonction pour charger les sous-aliments au chargement de la page
    function chargerSousAliments(mot) {
        $.ajax({
            type: "POST",
            url: "hierarchie_aliments.php",
            data: { mot: mot },
            success: function (response) {
                $("#hierarchie-container").html(response);
            },
            error: function () {
                alert("Une erreur s'est produite lors du chargement de la hiérarchie.");
            }
        });
    }
    // Charge les sous-aliments initiaux pour la catégorie "Aliment"
    chargerSousAliments("Aliment");

    // Fonction de gestion du clic sur un élément
    $(document).on("click", ".mot-cliquable", function () {
        var motClique = $(this).text();
        chargerSousAliments(motClique);
    });
});
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
        fetch('hierarchie_aliments.php', {
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
$(document).ready(function () {
    $(document).on('click', '.addToCart', function () {
        var nomCocktail = $(this).data('cocktail');
        $.ajax({
            type: 'POST',
            url: '../traitement_php/traitement_ajout_panier.php',
            data: { nomCocktail: nomCocktail },
            success: function (response) {
                console.log(nomCocktail);
                alert('Cocktail ajoute au panier avec succes !');
            },
            error: function (error) {
                console.error('Erreur lors de l\'ajout au panier :', error);
            }
        });
    });
});