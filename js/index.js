$(document).ready(function () {
    $(document).on('click', '.addToCart', function () {
        var nomCocktail = $(this).data('cocktail');
        $.ajax({
            type: 'POST',
            url: 'traitement_php/traitement_ajout_panier.php',
            data: { nomCocktail: nomCocktail },
            success: function (response) {
                console.log(nomCocktail);
                alert('Cocktail ajout� au panier avec succ�s !');
            },
            error: function (error) {
                console.error('Erreur lors de l\'ajout au panier :', error);
            }
        });
    });
    $('#resultsDropdown').hide();
        $('#searchTerm').on('input', performSearch);

        // Ajouter un gestionnaire d'�v�nements au clic pour les options de la liste d�roulante
        $('#resultsDropdown').on('click', 'option', function () {
            // R�cup�rer la valeur de l'option cliqu�e
            var selectedAliment = $(this).val();
            ajouterUnMot(selectedAliment,alimentsInclusArray);
        });

});
function updateCocktails() {
    $.ajax({
        type: 'POST',
        url: 'traitement_php/traitement_recherche_cocktail.php', 
        data: {
            alimentsInclus: alimentsInclusArray,
            alimentsExclus: alimentsExclusArray
        },
        success: function (response) {
            $('#cocktails-container').html(response);
        },
        error: function (error) {
            console.error('Erreur lors de la mise � jour des cocktails :', error);
        }
    });
}

function updateTableauxInfo() {
    console.log('Aliments inclus :', alimentsInclusArray);
    console.log('Aliments exclus :', alimentsExclusArray);

    var tableauxInfo = document.getElementById('tableaux-info');
    var tabAliments = document.getElementById('tab-aliments');

    if (alimentsInclusArray.length == 0 && alimentsExclusArray.length == 0) {
        tableauxInfo.innerHTML = "";
        tabAliments.style.display = 'none';
    } else {
        tabAliments.style.display = 'table';
        tableauxInfo.innerHTML = "Aliments inclus : <br/>" + alimentsInclusArray.join('.') +
            "<br/>Aliments exclus : <br/>" + alimentsExclusArray.join('.');
    }

    updateCocktails();
}

function supprimerLigne(button) {
    var row = button.parentNode.parentNode;

    // R�cup�rer le texte de la premi�re colonne (aliment)
    var aliment = row.cells[0].innerText;

    // Si la colonne d'origine est dans alimentsInclusArray, le retirer de alimentsInclusArray
    if (aliment != "") {
        var index = alimentsInclusArray.indexOf(aliment);
        if (index !== -1) {
            alimentsInclusArray.splice(index, 1);
        }
    } else {
        aliment = row.cells[3].innerText;
        // Sinon, le retirer de alimentsExclusArray
        var index = alimentsExclusArray.indexOf(aliment);
        if (index !== -1) {
            alimentsExclusArray.splice(index, 1);
        }
    }
    console.log('Aliments supprim� :', aliment);
    // Supprimer la ligne du tableau
    row.parentNode.removeChild(row);
    updateTableauxInfo();
}


function echangerColonne(button) {
    var row = button.parentNode.parentNode;

    // R�cup�rer le texte de la premi�re colonne (aliment)
    var aliment = row.cells[0].innerText;
    if (aliment != "") {
        // Retirer de alimentsInclusArray
        var index = alimentsInclusArray.indexOf(aliment);
        if (index !== -1) {
            alimentsInclusArray.splice(index, 1);
        }
        // Ajouter � alimentsExclusArray
        alimentsExclusArray.push(aliment);
        console.log('Aliments devient exclus :', aliment);
    } else {
        aliment = row.cells[3].innerText;
        // Retirer de alimentsExclusArray
        var index = alimentsExclusArray.indexOf(aliment);
        if (index !== -1) {
            alimentsExclusArray.splice(index, 1);
        }
        // Ajouter � alimentsInclusArray
        alimentsInclusArray.push(aliment);
        console.log('Aliments devient inclus :', aliment);
    }

    // Inverser la position des cellules 
    var cell1 = row.cells[0].innerHTML;
    var cell2 = row.cells[3].innerHTML;

    row.cells[0].innerHTML = cell2;
    row.cells[3].innerHTML = cell1;
    updateTableauxInfo();
}

function ajouterUnMot(aliment, aliments_inclus) {
    // Effectuer une requ�te AJAX pour appeler la fonction PHP qui ajoute le mot
    $.ajax({
        type: 'POST',
        url: 'index.php',
        data: { mot: aliment, alimentsInclusArray: aliments_inclus },
        success: function(response) {
            // La fonction PHP a �t� appel�e avec succ�s, la r�ponse est dans 'response'
            console.log("--------"+response+"------------");

        },
        error: function(error) {
            // Une erreur s'est produite lors de l'appel de la fonction PHP
            console.error("Erreur lors de l'appel de la fonction PHP :", error);
        }
    });
}







    function initializePage() {
        
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
                console.log('Erreur lors de la requ�te.');
            }
        });
    }

