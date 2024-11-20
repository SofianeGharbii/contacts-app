<?php
include 'db.php';
include 'ContactManager.php';

$contactManager = new ContactManager($conn);

function validateContactData($data)
{
    $errors = [];

    // Validation du prénom
    if (empty($data['first_name'])) {
        $errors[] = 'Le prénom est requis.';
    }

    // Validation du nom
    if (empty($data['last_name'])) {
        $errors[] = 'Le nom est requis.';
    }

    // Validation de l'âge
    if (empty($data['age']) || !filter_var($data['age'], FILTER_VALIDATE_INT)) {
        $errors[] = 'Un âge valide est requis.';
    }

    // Validation du pays
    if (empty($data['country'])) {
        $errors[] = 'Le pays est requis.';
    }

    // Validation de l'email
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Un email valide est requis.';
    }

    // Validation du numéro de téléphone
    if (empty($data['phone_number']) || !preg_match('/^[0-9]{8}$/', $data['phone_number'])) {
        $errors[] = 'Un numéro de téléphone valide est requis (8 chiffres).';
    }

    return $errors;
}

// Fonction pour renvoyer une réponse JSON
function jsonResponse($success, $errors = [])
{
    echo json_encode(['success' => $success, 'errors' => $errors]);
    exit;
}

// Créer un contact
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'age' => $_POST['age'],
        'country' => $_POST['country'],
        'email' => $_POST['email'],
        'phone_number' => $_POST['phone_number']
    ];

    $errors = validateContactData($data);

    if (empty($errors)) {
        $result = $contactManager->createContact($data);

        if (is_array($result) && isset($result['error'])) {
            jsonResponse(false, [$result['error']]);
        } elseif ($result) {
            jsonResponse(true);
        } else {
            jsonResponse(false, ["Erreur: Impossible d'ajouter le contact"]);
        }
    } else {
        jsonResponse(false, $errors);
    }
}


// Lire tous les contacts ou avec filtre de recherche
if (isset($_GET['action']) && $_GET['action'] == 'read') {
    // Vérifier si un terme de recherche est passé
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    $contacts = $contactManager->getAllContacts($searchTerm);
    echo json_encode($contacts);
}


// Mettre à jour un contact
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $data = [
        'id' => $_POST['id'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'age' => $_POST['age'],
        'country' => $_POST['country'],
        'email' => $_POST['email'],
        'phone_number' => $_POST['phone_number']
    ];

    $errors = validateContactData($data);

    if (empty($errors)) {
        if ($contactManager->updateContact($data)) {
            jsonResponse(true);
        } else {
            jsonResponse(false, ["Erreur: Impossible de mettre à jour le contact"]);
        }
    } else {
        jsonResponse(false, $errors);
    }
}

// Supprimer un contact
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];

    if ($contactManager->deleteContact($id)) {
        jsonResponse(true);
    } else {
        jsonResponse(false, ["Erreur: Impossible de supprimer le contact"]);
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'search') {
    $searchTerm = $_GET['query'];
    // Exemple de requête SQL pour chercher les contacts correspondant
    $query = "SELECT * FROM contacts WHERE first_name LIKE ? OR last_name LIKE ? OR country LIKE ? OR email LIKE ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute(["%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"]);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($contacts);
    exit;
}


$conn = null; // Fermer la connexion
