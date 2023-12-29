document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const suggestionsList = document.getElementById('suggestions');
    const searchForm = document.getElementById('searchForm');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.trim();

        // Effacer les suggestions existantes
        suggestionsList.innerHTML = '';

        // V�rifier si le terme de recherche n'est pas vide
        if (searchTerm.length > 0) {
            // Ex�cuter une requ�te AJAX pour obtenir des suggestions depuis le serveur
            // Remplacez cela par votre propre logique de suggestion
            // Vous pouvez utiliser fetch() ou XMLHttpRequest pour cela
            // Ensuite, ajoutez chaque suggestion � la liste
            // Par exemple, suggestionsList.innerHTML += `<li>${suggestion}</li>`;
        }
    });

    // G�rer la s�lection d'une suggestion
    suggestionsList.addEventListener('click', function (event) {
        if (event.target.tagName === 'LI') {
            searchInput.value = event.target.innerText;
            suggestionsList.innerHTML = ''; // Effacer les suggestions apr�s la s�lection
        }
    });

    // Emp�cher la soumission du formulaire si une suggestion est s�lectionn�e
    searchForm.addEventListener('submit', function (event) {
        if (suggestionsList.children.length > 0) {
            event.preventDefault();
        }
    });
});
