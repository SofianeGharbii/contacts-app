$(document).ready(function() {
    $('#contactForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: '../src/contacts.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redirection après succès
                    window.location.href = '../index.html';
                } else {
                    // Affichage des erreurs sous forme de liste
                    if (response.errors && response.errors.length > 0) {
                        let errorMessages = response.errors.map(function(error) {
                            return `<p>${error}</p>`;
                        }).join('');
                        $('#errorMessages').html(errorMessages).show();
                    }
                }
            },
            error: function() {
                // En cas d'erreur de serveur
                $('#errorMessages').html("<p>Une erreur s'est produite. Veuillez réessayer.</p>").show();
            }
        });
    });
});