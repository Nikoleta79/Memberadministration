<?php // Boekjaar sectie
namespace App\Models;
require_once '../config/config.php';

class FiscalYear {
    private $db;

    public function __construct() {
        $this->db = getDB(); // Verkrijg de databaseverbinding
    }

    // Maak een nieuw fiscaal jaar aan
    public function createFiscalYear($year) {
        $stmt = $this->db->prepare("INSERT INTO fiscalyears (year) VALUES (:year)"); // Bereid de SQL-query voor om een fiscaal jaar toe te voegen
        return $stmt->execute(['year' => $year]); // Voer de query uit met de opgegeven parameter
    }

    // Verkrijg alle fiscale jaren
    public function getAllFiscalYears() {
        $stmt = $this->db->query("SELECT * FROM fiscalyears"); // Voer een SQL-query uit om alle fiscale jaren op te halen
        $fiscalYears = $stmt->fetchAll(); // Geef alle resultaten terug als een array

        // Stel een cookie in voor de laatst bekeken boeken jaar
        if (!empty($fiscalYears)) {
            // Zet de cookie voor 30 dagen
            setcookie("last_accessed_fiscal_year", $fiscalYears[0]['year'], time() + (86400 * 30), "/"); 
        }

        return $fiscalYears; // Retourneer de fiscale jaren
    }

    // Verkrijg een specifiek fiscaal jaar op basis van ID
    public function getFiscalYearById($id) {
        $stmt = $this->db->prepare("SELECT * FROM fiscalyears WHERE id = :id"); // Bereid de SQL-query voor om een fiscaal jaar op te halen
        $stmt->execute(['id' => $id]); // Voer de query uit met de opgegeven ID
        return $stmt->fetch(); // Geef het resultaat terug
    }

    // Werk een fiscaal jaar bij
    public function updateFiscalYear($id, $year) {
        $stmt = $this->db->prepare("UPDATE fiscalyears SET year = :year WHERE id = :id"); // Bereid de SQL-query voor om een fiscaal jaar bij te werken
        return $stmt->execute(['id' => $id, 'year' => $year]); // Voer de query uit met de opgegeven parameters
    }

    // Verwijder een fiscaal jaar
    public function deleteFiscalYear($id) {
        $stmt = $this->db->prepare("DELETE FROM fiscalyears WHERE id = :id"); // Bereid de SQL-query voor om een fiscaal jaar te verwijderen
        return $stmt->execute(['id' => $id]); // Voer de query uit met de opgegeven ID
    }

    // Bereken de prijs op basis van leeftijd
    public function agePrice($age, $percentage = 100, $price) {
        if ($age < 8) {
            $price = ($price / 100) * 50; // 50% korting voor leeftijden onder de 8 jaar
        } else if ($age >= 8 && $age <= 12) {
            $price = ($price / 100) * 60; // 40% korting voor leeftijden tussen 8 en 12 jaar
        } else if ($age >= 13 && $age <= 17) {
            $price = ($price / 100) * 75; // 25% korting voor leeftijden tussen 13 en 17 jaar
        } else if ($age >= 18 && $age <= 50) {
            $price = $price; // Geen korting voor leeftijden tussen 18 en 50 jaar
        } else if ($age > 50) {
            $price = ($price / 100) * 55; // 45% korting voor leeftijden boven de 50 jaar
        } else {
            $price = $price; // Standaard prijs
        }

        return ($price / 100) * $percentage; // Geef de aangepaste prijs terug op basis van percentage
    }
}
?>



