// Fonction pour afficher les messages dans une popup
function showMessage(message, type = 'success') {
    // Créer une div pour le message
    let messageDiv = document.createElement('div');
    messageDiv.className = `popup-message ${type}`;
    messageDiv.textContent = message;

    document.body.appendChild(messageDiv);

    setTimeout(() => {
        document.body.removeChild(messageDiv);
    }, 3000);
}
