$(document).ready(function () {
    $(document).on('click', '.deco', function () {
        var deco = true;
        $.ajax({
            type: 'POST',
            url: '../traitement_php/traitement_profil.php',
            data: { deco: deco },
            success: function (response) {
                console.log('D�connexion r�ussie !');
                window.location.href = '../index.php';
            },
            error: function (error) {
                console.error('Erreur lors de la d�connexion :', error);
            }
        });
    });
    $(document).on('click', '.supprimerCompte', function () {
        var supprimerCompte = true;
        $.ajax({
            type: 'POST',
            url: '../traitement_php/traitement_profil.php',
            data: { supprimerCompte: supprimerCompte },
            success: function (response) {
                console.log('Suppresion du compte r�ussie !');
                window.location.href = '../index.php';
            },
            error: function (error) {
                console.error('Erreur lors de la supression :', error);
            }
        });
    });
});

