<?php
namespace App\Models;

require_once '../config/config.php';

class Member {
    private $db;

    public function __construct() {
        $this->db = getDB(); // Verkrijg de DB-verbinding
    }

    // Haal alle leden van een gezin op
    public function getAllMembersByFamily($familyId) {
        $stmt = $this->db->prepare("
            SELECT familymembers.*, membertypes.description AS member_type 
            FROM familymembers 
            JOIN membertypes ON familymembers.member_type_id = membertypes.id
            WHERE familymembers.family_id = :family_id
        ");
        $stmt->bindParam(':family_id', $familyId); // Bind de familie-ID parameter
        $stmt->execute(); // Voer de query uit
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Geef alle leden terug als een array
    }

    // Haal alle leden op
    public function getAllMembers() {
        $stmt = $this->db->prepare("
            SELECT familymembers.*, membertypes.description AS member_type 
            FROM familymembers 
            JOIN membertypes ON familymembers.member_type_id = membertypes.id
        ");
        $stmt->execute(); // Voer de query uit
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Geef alle leden terug als een array
    }

    // Verkrijg een enkel lid op basis van ID
    public function getMemberById($id) {
        $stmt = $this->db->prepare("SELECT * FROM familymembers WHERE id = :id"); // Bereid de SQL-query voor
        $stmt->bindParam(':id', $id); // Bind de ID parameter
        $stmt->execute(); // Voer de query uit
        return $stmt->fetch(\PDO::FETCH_ASSOC); // Geef het resultaat terug
    }

    // Voeg een nieuw lid toe
    public function addMember($familyId, $name, $dob, $memberTypeId) {
        $stmt = $this->db->prepare("
            INSERT INTO familymembers (family_id, name, date_of_birth, member_type_id) 
            VALUES (:family_id, :name, :dob, :member_type_id)
        ");
        $stmt->bindParam(':family_id', $familyId); // Bind de familie-ID parameter
        $stmt->bindParam(':name', $name); // Bind de naam parameter
        $stmt->bindParam(':dob', $dob); // Bind de geboortedatum parameter
        $stmt->bindParam(':member_type_id', $memberTypeId); // Bind de lidtype-ID parameter
        
        if ($stmt->execute()) { // Voer de query uit
            error_log("Lid succesvol toegevoegd!"); // Log succesbericht
        } else {
            error_log("Fout bij het toevoegen van lid!"); // Log foutbericht
        }
    }

    // Werk een bestaand lid bij
    public function updateMember($id, $name, $dob, $memberTypeId) {
        $stmt = $this->db->prepare("
            UPDATE familymembers 
            SET name = :name, date_of_birth = :dob, member_type_id = :member_type_id 
            WHERE id = :id
        ");
        $stmt->bindParam(':name', $name); // Bind de naam parameter
        $stmt->bindParam(':dob', $dob); // Bind de geboortedatum parameter
        $stmt->bindParam(':member_type_id', $memberTypeId); // Bind de lidtype-ID parameter
        $stmt->bindParam(':id', $id); // Bind de ID parameter
        $stmt->execute(); // Voer de query uit
    }

    // Verwijder een lid
    public function deleteMember($id) {
        $stmt = $this->db->prepare("DELETE FROM familymembers WHERE id = :id"); // Bereid de SQL-query voor om een lid te verwijderen
        $stmt->bindParam(':id', $id); // Bind de ID parameter
        $stmt->execute(); // Voer de query uit
    }

    // Haal alle lidtypes op
    public function getAllMemberTypes() {
        $stmt = $this->db->prepare("SELECT * FROM membertypes"); // Bereid de SQL-query voor om alle lidtypes op te halen
        $stmt->execute(); // Voer de query uit
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Geef alle lidtypes terug als een array
    }
}

