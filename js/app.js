document.addEventListener('DOMContentLoaded', function () {
    // Charger les contacts dès que la page est prête
    loadContacts();

    // Fonction de recherche dynamique dans le tableau des contacts
    document.getElementById('searchBox').addEventListener('keyup', searchTable);

    // Charger les contacts depuis le serveur
    function loadContacts(searchTerm = '') {
        fetch(`src/contacts.php?action=read&search=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                let tbody = document.getElementById('contactsBody');
                tbody.innerHTML = ''; // Réinitialiser le corps du tableau
                data.forEach(contact => {
                    let row = `<tr>
                        <td>${contact.first_name}</td>
                        <td>${contact.last_name}</td>
                        <td>${contact.age}</td>
                        <td>${contact.country}</td>
                        <td>${contact.email}</td>
                        <td>${contact.phone_number}</td>
                        <td>
                            <a href="templates/form_create_edit.php?action=edit&id=${contact.id}" class="btn-edit">Éditer</a> |
                            <a href="#" onclick="deleteContact(${contact.id})" class="btn-delete">Supprimer</a>
                        </td>
                    </tr>`;
                    tbody.innerHTML += row;
                });
            })
            .catch(error => showMessage('Erreur lors du chargement des contacts.', 'error'));
    }

    // Supprimer un contact
    window.deleteContact = function (id) {
        if (confirm('Voulez-vous vraiment supprimer ce contact ?')) {
            fetch(`src/contacts.php?action=delete&id=${id}`, { method: 'GET' })
                .then(response => response.text())
                .then(data => {
                    showMessage(data, 'success');
                    loadContacts(); // Recharger la liste des contacts
                })
                .catch(error => showMessage('Erreur lors de la suppression du contact.', 'error'));
        }
    };

    // Fonction de recherche dynamique dans le tableau des contacts
    function searchTable() {
        let input = document.getElementById("searchBox");
        let searchTerm = input.value.trim().toLowerCase();
        loadContacts(searchTerm); // Recharger les contacts avec le terme de recherche
    }

    // Vérifier si on est dans le formulaire de création/édition
    if (document.getElementById("contactForm")) {
        let action = document.getElementById("contactForm").querySelector("input[name='action']").value;

        // Gérer la soumission du formulaire pour l'ajout ou la mise à jour
        document.getElementById("contactForm").addEventListener("submit", function (event) {
            event.preventDefault();

            let formData = new FormData(this);

            // Effectuer la requête AJAX pour envoyer les données
            let url = action === 'create' ? 'src/contacts.php' : `src/contacts.php?action=update&id=${formData.get('id')}`;
            fetch(url, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    showMessage(data, 'success');
                    setTimeout(() => window.location.href = 'index.html', 1500); // Rediriger après un succès
                })
                .catch(error => showMessage('Erreur lors de l\'envoi du formulaire.', 'error'));
        });

        // Charger les données existantes si formulaire d'édition
        if (action === 'update') {
            let urlParams = new URLSearchParams(window.location.search);
            let contactId = urlParams.get('id');

            fetch(`src/contacts.php?action=read&id=${contactId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('first_name').value = data.first_name;
                        document.getElementById('last_name').value = data.last_name;
                        document.getElementById('age').value = data.age;
                        document.getElementById('country').value = data.country;
                        document.getElementById('email').value = data.email;
                        document.getElementById('phone_number').value = data.phone_number;
                    } else {
                        showMessage('Aucun contact trouvé pour cet ID.', 'error');
                    }
                })
                .catch(error => showMessage('Erreur lors du chargement du contact.', 'error'));
        }
    }
});
