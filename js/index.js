$(document).ready(function () {
    $(document).on('click', '.addToCart', function () {
        var nomCocktail = $(this).data('cocktail');
        $.ajax({
            type: 'POST',
            url: 'traitement_php/traitement_ajout_panier.php',
            data: { nomCocktail: nomCocktail },
            success: function (response) {
                console.log(nomCocktail);
                alert('Cocktail ajouté au panier avec succès !');
            },
            error: function (error) {
                console.error('Erreur lors de l\'ajout au panier :', error);
            }
        });
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
            console.error('Erreur lors de la mise à jour des cocktails :', error);
        }
    });
}

function updateTableauxInfo() {
    console.log('Aliments inclus :', alimentsInclusArray);
    console.log('Aliments exclus :', alimentsExclusArray);
    var tableauxInfo = document.getElementById('tableaux-info');
    tableauxInfo.innerHTML = "Aliments inclus : <br/>" + alimentsInclusArray.join('.') +
        "<br/>Aliments exclus : <br/>" + alimentsExclusArray.join('.');
    updateCocktails();
}

function supprimerLigne(button) {
    var row = button.parentNode.parentNode;

    // Récupérer le texte de la première colonne (aliment)
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
    console.log('Aliments supprimé :', aliment);
    // Supprimer la ligne du tableau
    row.parentNode.removeChild(row);
    updateTableauxInfo();
}


function echangerColonne(button) {
    var row = button.parentNode.parentNode;

    // Récupérer le texte de la première colonne (aliment)
    var aliment = row.cells[0].innerText;
    if (aliment != "") {
        // Retirer de alimentsInclusArray
        var index = alimentsInclusArray.indexOf(aliment);
        if (index !== -1) {
            alimentsInclusArray.splice(index, 1);
        }
        // Ajouter à alimentsExclusArray
        alimentsExclusArray.push(aliment);
        console.log('Aliments devient exclus :', aliment);
    } else {
        aliment = row.cells[3].innerText;
        // Retirer de alimentsExclusArray
        var index = alimentsExclusArray.indexOf(aliment);
        if (index !== -1) {
            alimentsExclusArray.splice(index, 1);
        }
        // Ajouter à alimentsInclusArray
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
