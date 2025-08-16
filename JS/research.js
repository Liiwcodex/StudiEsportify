
const suggestions = ["jeux de tir à la première personne", "Les jeux de bataille en arène en ligne multijoueur"];

function fetchEventDetails(suggestion) {
    fetch(`/StudiProjetEsport/php/getEvent.php?title=${encodeURIComponent(suggestion)}`)
        .then(response => response.json())
        .then(data => {
            const eventDetailsDiv = document.getElementById('event-details');
            eventDetailsDiv.innerHTML = '';

            if (data) {
                eventDetailsDiv.innerHTML = `
                    
                    <h2>${data.titre}</h2>
                    <p>${data.description}</p>
                    <p>Date: ${data.date}</p>
                    <p>Lieu: ${data.Adresse}</p>
                `;
            } else {
                eventDetailsDiv.innerHTML = '<p>Aucun événement trouvé.</p>';
            }
        })
        .catch(error => console.error('Erreur:', error));
}

function showSuggestions(value) {
    const suggestionBox = document.getElementById('suggestions');
    suggestionBox.innerHTML = '';
    if (value) {
        const filteredSuggestions = suggestions.filter(suggestion =>
            suggestion.toLowerCase().includes(value.toLowerCase())
        );
        filteredSuggestions.forEach(suggestion => {
            const suggestionItem = document.createElement('a');
            suggestionItem.classList.add('suggestion-item');
            suggestionItem.textContent = suggestion;
            suggestionItem.href = `#${suggestion}`;
            suggestionItem.onclick = (e) => {
                e.preventDefault();
                document.getElementById('search').value = suggestion;
                suggestionBox.innerHTML = '';
                fetchEventDetails(suggestion); // Appel pour récupérer les détails de l'événement
            };
            const suggestionContainer = document.createElement('div');
            suggestionContainer.appendChild(suggestionItem);
            suggestionBox.appendChild(suggestionContainer);
        });
    }
}
