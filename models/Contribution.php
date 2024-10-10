<?php
namespace App\Models;

// Vereist het configuratiebestand
require_once '../config/config.php';

class Contribution {
    private $db;

    public function __construct() {
        // Verkrijg de databaseverbinding
        $this->db = getDB(); 
    }

    // Haal alle bijdragen op, eventueel gefilterd op gezins-ID
    public function getAllContributions() {
        $query = "
            SELECT 
                contributions.id, 
                familymembers.name AS member_name, 
                TIMESTAMPDIFF(YEAR, familymembers.date_of_birth, CURDATE()) AS age, 
                membertypes.description AS member_type, 
                contributions.amount, 
                fiscalyears.year AS fiscal_year
            FROM 
                contributions
            INNER JOIN familymembers ON contributions.member_id = familymembers.id
            INNER JOIN membertypes ON familymembers.member_type_id = membertypes.id
            INNER JOIN fiscalyears ON contributions.fiscal_year_id = fiscalyears.id
        ";

        // Bereid de query voor en voer deze uit
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Geef alle bijdragen terug
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Haal alle bijdragen op op basis van gezins-ID
    public function getAllContributionsByFamilyId($familyId) {
        $query = "
            SELECT 
                contributions.id, 
                familymembers.name AS member_name, 
                TIMESTAMPDIFF(YEAR, familymembers.date_of_birth, CURDATE()) AS age, 
                membertypes.description AS member_type, 
                contributions.amount, 
                fiscalyears.year AS fiscal_year
            FROM 
                contributions
            INNER JOIN familymembers ON contributions.member_id = familymembers.id
            INNER JOIN membertypes ON familymembers.member_type_id = membertypes.id
            INNER JOIN fiscalyears ON contributions.fiscal_year_id = fiscalyears.id
            WHERE 
                familymembers.family_id = :familyId
        ";

        // Bereid de query voor en bind de parameters
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':familyId', $familyId, \PDO::PARAM_INT); 
        $stmt->execute();

        // Geef alle bijdragen voor het gezin terug
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Haal een enkele bijdrage op aan de hand van zijn ID
    public function getContributionById($id) {
        $query = "
            SELECT 
                contributions.id, 
                familymembers.name AS member_name, 
                TIMESTAMPDIFF(YEAR, familymembers.date_of_birth, CURDATE()) AS age, 
                membertypes.description AS member_type, 
                contributions.amount, 
                fiscalyears.year AS fiscal_year
            FROM 
                contributions
            INNER JOIN familymembers ON contributions.member_id = familymembers.id
            INNER JOIN membertypes ON familymembers.member_type_id = membertypes.id
            INNER JOIN fiscalyears ON contributions.fiscal_year_id = fiscalyears.id
            WHERE 
                contributions.id = :id
        ";

        // Bereid de query voor en bind de parameters
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT); 
        $stmt->execute();

        // Geef de specifieke bijdrage terug
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Maak een nieuwe bijdrage aan
    public function createContribution($member_id, $amount, $fiscal_year_id, $family_id) {
        $query = "INSERT INTO contributions (member_id, amount, fiscal_year_id, family_id) 
                  VALUES (:member_id, :amount, :fiscal_year_id, :family_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':member_id', $member_id, \PDO::PARAM_INT); 
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':fiscal_year_id', $fiscal_year_id, \PDO::PARAM_INT); 
        $stmt->bindParam(':family_id', $family_id, \PDO::PARAM_INT); 
        
        // Voer de invoegactie uit en geef de status terug
        return $stmt->execute();
    }

    // Werk een bijdrage bij
    public function updateContribution($id, $amount, $fiscal_year_id) {
        $query = "
            UPDATE contributions 
            SET amount = :amount, fiscal_year_id = :fiscal_year_id 
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':fiscal_year_id', $fiscal_year_id, \PDO::PARAM_INT); 
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT); 

        // Voer de updateactie uit en geef de status terug
        return $stmt->execute();
    }

    // Verwijder een bijdrage
    public function deleteContribution($id) {
        $query = "DELETE FROM contributions WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT); 

        // Voer de verwijderactie uit en geef de status terug
        return $stmt->execute();
    }
}
?>
