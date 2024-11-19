document.addEventListener('DOMContentLoaded', function () {
    // Charger les contacts dès que la page est prête
    loadContacts();

    // Fonction de recherche dynamique dans le tableau des contacts
    document.getElementById('searchBox').addEventListener('keyup', searchTable);

    // Fonction pour charger les contacts
    function loadContacts() {
        fetch('src/contacts.php?action=read')
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
                            <a href="templates/form_create_edit.php?action=edit&id=${contact.id}">Éditer</a> |
                            <a href="src/contacts.php?action=delete&id=${contact.id}" onclick="return confirm('Voulez-vous vraiment supprimer ce contact ?')">Supprimer</a>
                        </td>
                    </tr>`;
                    tbody.innerHTML += row;
                });
            })
            .catch(error => console.error('Erreur lors du chargement des contacts:', error));
    }

    // Fonction de recherche dynamique dans le tableau des contacts
    function searchTable() {
        let input = document.getElementById("searchBox");
        let filter = input.value.toLowerCase();
        let table = document.getElementById("contactsTable");
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) { // Ignorer la première ligne (en-têtes)
            let td = tr[i].getElementsByTagName("td");
            let found = false;
            
            for (let j = 0; j < td.length - 1; j++) { // Ignorer la dernière colonne (Actions)
                if (td[j]) {
                    let txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                    }
                }
            }

            if (found) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }

    // Vérifier si on est dans le formulaire de création/édition
    if (document.getElementById("contactForm")) {
        let action = document.getElementById("contactForm").querySelector("input[name='action']").value;

        // Gérer la soumission du formulaire pour l'ajout ou la mise à jour
        document.getElementById("contactForm").addEventListener("submit", function (event) {
            event.preventDefault();

            let formData = new FormData(this);

            // Effectuer la requête AJAX pour envoyer les données à `contacts.php`
            let url = action === 'create' ? 'src/contacts.php' : `src/contacts.php?action=update&id=${formData.get('id')}`;
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data.includes('réussi')) {
                    window.location.href = 'index.html'; // Rediriger vers la page principale après l'action réussie
                }
            })
            .catch(error => console.error('Erreur lors de l\'envoi du formulaire:', error));
        });

        // Si c'est un formulaire d'édition, charger les données existantes
        if (action === 'update') {
            let urlParams = new URLSearchParams(window.location.search);
            let contactId = urlParams.get('id');

            // Charger les données du contact pour l'édition
            fetch(`src/contacts.php?action=read&id=${contactId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        // Remplir le formulaire avec les données récupérées
                        document.getElementById('first_name').value = data.first_name;
                        document.getElementById('last_name').value = data.last_name;
                        document.getElementById('age').value = data.age;
                        document.getElementById('country').value = data.country;
                        document.getElementById('email').value = data.email;
                        document.getElementById('phone_number').value = data.phone_number;
                    } else {
                        alert('Aucun contact trouvé pour cet ID.');
                    }
                })
                .catch(error => console.error('Erreur lors du chargement du contact:', error));
        }
    }
});
