<?php
class ContactManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createContact($data) {
        $sql = "INSERT INTO contacts (first_name, last_name, age, country, email, phone_number)
                VALUES (:first_name, :last_name, :age, :country, :email, :phone_number)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':age', $data['age']);
        $stmt->bindParam(':country', $data['country']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone_number', $data['phone_number']);

        return $stmt->execute();
    }

    public function getAllContacts() {
        $sql = "SELECT * FROM contacts";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateContact($data) {
        $sql = "UPDATE contacts 
                SET first_name = :first_name, last_name = :last_name, age = :age, 
                    country = :country, email = :email, phone_number = :phone_number 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':age', $data['age']);
        $stmt->bindParam(':country', $data['country']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone_number', $data['phone_number']);
        $stmt->bindParam(':id', $data['id']);

        return $stmt->execute();
    }

    public function deleteContact($id) {
        $sql = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
