document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const suggestionsList = document.getElementById('suggestions');
    const searchForm = document.getElementById('searchForm');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.trim();

        // Efface les suggestions existantes
        suggestionsList.innerHTML = '';

    });

    // Gére la sélection d'une suggestion
    suggestionsList.addEventListener('click', function (event) {
        if (event.target.tagName === 'LI') {
            searchInput.value = event.target.innerText;
            suggestionsList.innerHTML = ''; // Efface les suggestions après la sélection
        }
    });

    // Empêche la soumission du formulaire si une suggestion est sélectionnée
    searchForm.addEventListener('submit', function (event) {
        if (suggestionsList.children.length > 0) {
            event.preventDefault();
        }
    });
});
