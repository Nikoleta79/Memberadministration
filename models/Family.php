<?php
namespace App\Models;

use PDO;
use Exception;

require_once '../config/config.php'; 

class Family {
    private $db;

    public function __construct() {
        $this->db = getDB(); // Verkrijg de DB-verbinding
    }

    // Verkrijg alle gezinnen
    public function getAllFamilies() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Families"); // Bereid de SQL-query voor
            $stmt->execute(); // Voer de query uit
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Geef alle resultaten terug als associatieve array
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // Geef een foutmelding terug als er een uitzondering optreedt
        }
    }

    // Verkrijg een specifiek gezin op basis van ID
    public function getFamilyById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Families WHERE id = :id"); // Bereid de SQL-query voor met een parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind de parameter voor de ID
            $stmt->execute(); // Voer de query uit
            return $stmt->fetch(PDO::FETCH_ASSOC); // Geef het resultaat terug als associatieve array
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // Geef een foutmelding terug als er een uitzondering optreedt
        }
    }

    // Voeg een nieuw gezin toe
    public function addFamily($name, $address) {
        try {
            $stmt = $this->db->prepare("INSERT INTO Families (name, address) VALUES (:name, :address)"); // Bereid de SQL-query voor om een gezin toe te voegen
            $stmt->bindParam(':name', $name); // Bind de parameter voor de naam
            $stmt->bindParam(':address', $address); // Bind de parameter voor het adres
            return $stmt->execute(); // Voer de query uit
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // Geef een foutmelding terug als er een uitzondering optreedt
        }
    }

    // Werk een gezin bij
    public function updateFamily($id, $name, $address) {
        try {
            $stmt = $this->db->prepare("UPDATE Families SET name = :name, address = :address WHERE id = :id"); // Bereid de SQL-query voor om een gezin bij te werken
            $stmt->bindParam(':name', $name); // Bind de parameter voor de naam
            $stmt->bindParam(':address', $address); // Bind de parameter voor het adres
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind de parameter voor de ID
            return $stmt->execute(); // Voer de query uit
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // Geef een foutmelding terug als er een uitzondering optreedt
        }
    }

    // Verwijder een gezin
    public function deleteFamily($id) {
        try {
            $this->db->beginTransaction(); // Begin een transactie

            // Verwijder bijdragen van gezinsleden
            $stmt = $this->db->prepare("DELETE FROM contributions WHERE member_id IN (SELECT id FROM familymembers WHERE family_id = :family_id)"); // Bereid de SQL-query voor om bijdragen te verwijderen
            $stmt->bindParam(':family_id', $id, PDO::PARAM_INT); // Bind de parameter voor het gezin-ID
            $stmt->execute(); // Voer de query uit

            // Verwijder gezinsleden
            $stmt = $this->db->prepare("DELETE FROM familymembers WHERE family_id = :family_id"); // Bereid de SQL-query voor om gezinsleden te verwijderen
            $stmt->bindParam(':family_id', $id, PDO::PARAM_INT); // Bind de parameter voor het gezin-ID
            $stmt->execute(); // Voer de query uit

            // Verwijder gezin
            $stmt = $this->db->prepare("DELETE FROM Families WHERE id = :id"); // Bereid de SQL-query voor om het gezin te verwijderen
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind de parameter voor de ID
            $stmt->execute(); // Voer de query uit

            $this->db->commit(); // Bevestig de transactie
            return true; // Geef true terug als de operatie succesvol was
        } catch (Exception $e) {
            $this->db->rollBack(); // Rol de transactie terug bij een fout
            return ['error' => $e->getMessage()]; // Geef een foutmelding terug als er een uitzondering optreedt
        }
    }
}
?>

