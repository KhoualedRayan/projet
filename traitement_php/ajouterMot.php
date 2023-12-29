<?php
// Inclure les d�pendances ou les configurations n�cessaires
// ...

// V�rifier si la requ�te POST contient le mot et le tableau d'aliments inclus
if (isset($_POST['mot']) && isset($_POST['alimentsInclusArray'])) {
    $mot = $_POST['mot'];
    $alimentsInclusArray = $_POST['alimentsInclusArray'];

    // Appeler la fonction ajouterMot avec les param�tres n�cessaires
    ajouterMot($mot, $alimentsInclusArray);

    // R�pondre avec un message de succ�s (ou toute autre information n�cessaire)
    echo 'Mot ajout� avec succ�s';
} else {
    // R�pondre avec un message d'erreur si les donn�es n�cessaires ne sont pas pr�sentes
    echo 'Erreur : Param�tres manquants';
}

// Fonction pour ajouter un mot � un tableau
function ajouterMot($mot, &$alimentsInclusArray)
{
    // Rajouter $mot au tableau appropri�
    $alimentsInclusArray[] = $mot;

    // Retourner le tableau en tant que r�ponse JSON
    echo json_encode($alimentsInclusArray);
}

?>
