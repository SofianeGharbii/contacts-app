// Fonction pour afficher les messages dans une popup
function showMessage(message, type = 'success') {
    // Créer une div pour le message
    let messageDiv = document.createElement('div');
    messageDiv.className = `popup-message ${type}`;
    messageDiv.textContent = message;

    // Ajouter la popup au corps du document
    document.body.appendChild(messageDiv);

    // Supprimer automatiquement la popup après 3 secondes
    setTimeout(() => {
        document.body.removeChild(messageDiv);
    }, 3000);
}
