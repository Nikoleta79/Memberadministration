<?php
// Start output buffering om output te voorkomen vóór headers
namespace App\Controllers;
ob_start();

use App\Models\Contribution;
use App\Models\Member;
use App\Models\FiscalYear;
use App\Models\MemberType;

// Inclusie van benodigde modelbestanden
require_once '../models/Contribution.php';
require_once '../models/Member.php';
require_once '../models/FiscalYear.php';
require_once '../models/MemberType.php';

class ContributionController {
    private $contributionModel; // Model voor bijdragen
    private $memberModel; // Model voor leden
    private $fiscalYearModel; // Model voor fiscale jaren
    private $memberTypeModel; // Model voor lidtypen

    public function __construct() {
        // Initialiseer de modellen
        $this->contributionModel = new Contribution();
        $this->memberModel = new Member();
        $this->fiscalYearModel = new FiscalYear();
        $this->memberTypeModel = new MemberType();
    }

    // Toon alle bijdragen voor een specifieke familie
    public function getAllContributions($familyId = null) {
        if ($familyId) {
            // Verkrijg bijdragen op basis van het familie-ID
            $contributions = $this->contributionModel->getAllContributionsByFamilyId($familyId);
        } else {
            // Verkrijg alle bijdragen
            $contributions = $this->contributionModel->getAllContributions();
        }
        // Inclusie van de weergave voor het lijst van bijdragen
        require '../views/contribution/list.php'; 
    }

    // Maak een nieuwe bijdrage aan
    public function createContribution($familyId = null) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verkrijg gegevens van het formulier
            $member_id = $_POST['member_id'];
            $amount = $_POST['amount'];
            $fiscal_year_id = $_POST['fiscal_year_id'];

            // Voeg de bijdrage toe
            $this->contributionModel->createContribution($member_id, $amount, $fiscal_year_id, $familyId);

            // Gebruik trim() en urlencode() om family_id te saneren
            $familyIdEncoded = urlencode(trim($familyId));

            // Redirect naar de lijst met bijdragen
            header('Location: ../public/index.php?action=listContributions&familyId=' . $familyIdEncoded);
            exit();
        } else {
            // Verkrijg alle leden, fiscale jaren en lidtypen
            $members = $this->memberModel->getAllMembers();
            $fiscalYears = $this->fiscalYearModel->getAllFiscalYears();
            $memberTypes = $this->memberTypeModel->getAllMemberTypes();
            // Inclusie van de weergave voor het aanmaken van een bijdrage
            require '../views/contribution/create.php';
        }
    }

    // Bewerken van een bijdrage
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verkrijg gegevens van het formulier
            $member_id = $_POST['member_id'];
            $amount = $_POST['amount'];
            $fiscal_year_id = $_POST['fiscal_year_id'];
            $family_id = trim($_POST['family_id']);  // Verwijder extra tekens

            // Werk de bijdrage bij
            $this->contributionModel->updateContribution($id, $amount, $fiscal_year_id);

            // Sanitize en redirect met family_id
            $family_id_encoded = urlencode($family_id);
            header('Location: ../public/index.php?action=listContributions&familyId=' . $family_id_encoded);
            exit();
        } else {
            // Verkrijg de bijdrage op basis van ID
            $contribution = $this->contributionModel->getContributionById($id);
            $members = $this->memberModel->getAllMembers();
            $fiscalYears = $this->fiscalYearModel->getAllFiscalYears();
            $memberTypes = $this->memberTypeModel->getAllMemberTypes();
            // Inclusie van de weergave voor het bewerken van een bijdrage
            require '../views/contribution/edit.php';
        }
    }

    // Verwijder een bijdrage
    public function delete($id) {
        // Verwijder de bijdrage op basis van ID
        $this->contributionModel->deleteContribution($id);
        
        // Zorg ervoor dat family_id gesaneerd is vóór redirect
        $family_id_encoded = urlencode(trim($_GET['familyId']));
        header('Location: ../public/index.php?action=listContributions&familyId=' . $family_id_encoded);
        exit();
    }
}


ob_end_flush();

