function verifNumeroTel() {
    var numero = document.getElementById("tel").value;
    var pattern = /^0\d{9}$/;
    var erreur = document.getElementById("telError");

    if (!pattern.test(numero)) {
        erreur.textContent = "Le num�ro de t�l�phone doit commencer par 0 et avoir 9 chiffres (t�l�phone fran�ais).";
        return false;
    }

    erreur.textContent = ""; // Efface le message d'erreur s'il n'y a pas d'erreur
    return true;
}

function validateForm() {
    return verifNumeroTel();
}
