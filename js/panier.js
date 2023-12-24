$(document).ready(function () {
    $(document).on('click', '.supprimerDuPanier', function () {
        var idCocktail = $(this).data('cocktail');
        $.ajax({
            type: 'POST',
            url: '../traitement_php/traitement_supprimer_panier.php',
            data: { idCocktail: idCocktail },
            success: function (response) {
                console.log(idCocktail);
                location.reload();
                alert('Cocktail supprim� du panier avec succ�s !');
                
            },
            error: function (error) {
                console.error('Erreur lors de la suppresion du panier :', error);
            }
        });
    });
});
