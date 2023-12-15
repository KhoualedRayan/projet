function ajouterAuPanier(nomCocktail) {
    $.ajax({
        type: "POST",
        url: "ajouter_au_panier.php",
        data: { nomCocktail: nomCocktail },
        success: function (response) {
            alert("Produit ajouté au panier !");
        },
        error: function (error) {
            console.error("Erreur lors de l'ajout au panier : ", error);
        }
    });
}
