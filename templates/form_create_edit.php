<?php
// Vérification de l'action (edit ou create)
$action = isset($_GET['action']) && $_GET['action'] == 'edit' ? 'update' : 'create';

// Initialisation des variables de contact
$contact = [];

// Si c'est une action d'édition, récupérer les données du contact
if ($action == 'update' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour récupérer le contact en fonction de l'ID
    include '../src/db.php'; // Inclure la connexion à la base de données

    $sql = "SELECT * FROM contacts WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contact) {
        // Si le contact n'est pas trouvé, rediriger ou afficher un message d'erreur
        echo "Aucun contact trouvé pour cet ID.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Contacts</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Gestion des Contacts</h1>

        <!-- Conteneur pour les messages d'erreur -->
        <div id="errorMessages" class="alert alert-danger" style="display: none;"></div>

        <!-- Formulaire de contact -->
        <form id="contactForm" method="POST" action="contacts.php">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <?php if ($action == 'update') { ?>
                <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
            <?php } ?>
            <div class="form-group">
                <label for="first_name">Prénom</label>
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Prénom" value="<?php echo $contact['first_name'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Nom</label>
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Nom" value="<?php echo $contact['last_name'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="age">Âge</label>
                <input type="number" class="form-control" name="age" id="age" placeholder="Âge" value="<?php echo $contact['age'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="country">Pays</label>
                <input type="text" class="form-control" name="country" id="country" placeholder="Pays" value="<?php echo $contact['country'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $contact['email'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Numéro de Téléphone</label>
                <input type="tel" class="form-control" name="phone_number" id="phone_number" placeholder="Numéro de Téléphone" value="<?php echo $contact['phone_number'] ?? ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $action == 'create' ? 'Ajouter' : 'Mettre à jour'; ?></button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
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
                            window.location.href = '../index.html';
                        } else {
                            $('#errorMessages').html(response.errors.join('<br>')).show();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
