document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const suggestionsList = document.getElementById('suggestions');
    const searchForm = document.getElementById('searchForm');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.trim();

        // Effacer les suggestions existantes
        suggestionsList.innerHTML = '';

        // Vérifier si le terme de recherche n'est pas vide
        if (searchTerm.length > 0) {
            // Exécuter une requête AJAX pour obtenir des suggestions depuis le serveur
            // Remplacez cela par votre propre logique de suggestion
            // Vous pouvez utiliser fetch() ou XMLHttpRequest pour cela
            // Ensuite, ajoutez chaque suggestion à la liste
            // Par exemple, suggestionsList.innerHTML += `<li>${suggestion}</li>`;
        }
    });

    // Gérer la sélection d'une suggestion
    suggestionsList.addEventListener('click', function (event) {
        if (event.target.tagName === 'LI') {
            searchInput.value = event.target.innerText;
            suggestionsList.innerHTML = ''; // Effacer les suggestions après la sélection
        }
    });

    // Empêcher la soumission du formulaire si une suggestion est sélectionnée
    searchForm.addEventListener('submit', function (event) {
        if (suggestionsList.children.length > 0) {
            event.preventDefault();
        }
    });
});
