$(document).ready(function () {
    $(document).on('click', '.supprimerDuPanier', function () {
        var nomCocktail = $(this).data('cocktail');
        $.ajax({
            type: 'POST',
            url: 'traitement_php/traitement_supprimer_panier.php',
            data: { id: id },
            success: function (response) {
                console.log(nomCocktail);
                alert('Cocktail ajout� au panier avec succ�s !');
            },
            error: function (error) {
                console.error('Erreur lors de l\'ajout au panier :', error);
            }
        });
    });
});
