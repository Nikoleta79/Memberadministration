<?php
namespace App\Models;

use PDO;
use Exception;

require_once '../config/config.php'; 

class MemberType {
    private $db;

    public function __construct() {
        $this->db = getDB(); // Verkrijg de DB-verbinding
    }

    // Maak een nieuw lidtype aan
    public function createMemberType($description) {
        try {
            $stmt = $this->db->prepare("INSERT INTO membertypes (description) VALUES (:description)");
            return $stmt->execute(['description' => $description]); // Voer de query uit en retourneer het resultaat
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // Retourneer een foutbericht als er een uitzondering optreedt
        }
    }

    // Haal alle lidtypes op
    public function getAllMemberTypes() {
        try {
            $stmt = $this->db->query("SELECT * FROM membertypes"); // Bereid de SQL-query voor
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Geef alle lidtypes terug als een array
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // Retourneer een foutbericht als er een uitzondering optreedt
        }
    }

    // Haal lidtype op op basis van ID
    public function getMemberTypeById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM membertypes WHERE id = :id"); // Bereid de SQL-query voor
            $stmt->execute(['id' => $id]); // Voer de query uit
            return $stmt->fetch(PDO::FETCH_ASSOC); // Geef het resultaat terug
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // Retourneer een foutbericht als er een uitzondering optreedt
        }
    }

    // Werk een lidtype bij
    public function updateMemberType($id, $description) {
        try {
            $stmt = $this->db->prepare("UPDATE membertypes SET description = :description WHERE id = :id"); // Bereid de SQL-query voor
            return $stmt->execute([
                'id' => $id, // Bind de ID parameter
                'description' => $description // Bind de beschrijving parameter
            ]); // Voer de query uit en retourneer het resultaat
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // Retourneer een foutbericht als er een uitzondering optreedt
        }
    }

    // Verwijder een lidtype
    public function deleteMemberType($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM membertypes WHERE id = :id"); // Bereid de SQL-query voor
            return $stmt->execute(['id' => $id]); // Voer de query uit en retourneer het resultaat
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // Retourneer een foutbericht als er een uitzondering optreedt
        }
    }
}
?>

