<?php
// Inclure les dépendances ou les configurations nécessaires
// ...

// Vérifier si la requête POST contient le mot et le tableau d'aliments inclus
if (isset($_POST['mot']) && isset($_POST['alimentsInclusArray'])) {
    $mot = $_POST['mot'];
    $alimentsInclusArray = $_POST['alimentsInclusArray'];

    // Appeler la fonction ajouterMot avec les paramètres nécessaires
    ajouterMot($mot, $alimentsInclusArray);

    // Répondre avec un message de succès (ou toute autre information nécessaire)
    echo 'Mot ajouté avec succès';
} else {
    // Répondre avec un message d'erreur si les données nécessaires ne sont pas présentes
    echo 'Erreur : Paramètres manquants';
}

// Fonction pour ajouter un mot à un tableau
function ajouterMot($mot, &$alimentsInclusArray)
{
    // Rajouter $mot au tableau approprié
    $alimentsInclusArray[] = $mot;

    // Retourner le tableau en tant que réponse JSON
    echo json_encode($alimentsInclusArray);
}

?>
