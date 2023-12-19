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
});
