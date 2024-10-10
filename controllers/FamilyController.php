<?php

namespace App\Controllers;

use App\Models\Family;
use App\Models\Member;
use App\Models\Contribution;

require '../models/Family.php';
require '../models/Member.php';
require '../models/Contribution.php';

class FamilyController {
    private $familyModel; // Model voor families
    private $memberModel; // Model voor leden
    private $contributionModel; // Model voor bijdragen

    public function __construct() {
        // Initialiseer de modellen
        $this->familyModel = new Family();
        $this->memberModel = new Member();
        $this->contributionModel = new Contribution();
    }

    // Lijst alle families met totale bijdragebedragen
    public function listFamilies() {
        // Start de sessie om toegang te krijgen tot sessievariabelen
        session_start();
    
        // Controleer of de gebruiker is ingelogd
        if (!isset($_SESSION['user_id'])) {
            // Redirect naar de inlogpagina als niet ingelogd
            header("Location: ../views/auth/login.php");
            exit(); // Zorg ervoor dat er geen verdere code wordt uitgevoerd
        }
    
        // Haal families op als de gebruiker is ingelogd
        $families = $this->familyModel->getAllFamilies();
        include '../views/family/list.php'; // Laad de weergave voor de lijst van families
    }
    
    // Toon het formulier voor het aanmaken van een nieuwe familie
    public function createFamily() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $address = $_POST['address'];
            $this->familyModel->addFamily($name, $address); // Voeg de familie toe
            header('Location: ../public/index.php?action=listFamilies'); // Redirect naar de lijst van families
        } else {
            include '../views/family/create.php'; // Laad de weergave voor het aanmaken van een familie
        }
    }

    // Toon het formulier voor het bewerken van een bestaande familie
    public function editFamily($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $address = $_POST['address'];
            $this->familyModel->updateFamily($id, $name, $address); // Werk de familie bij
            header('Location: ../public/index.php?action=listFamilies'); // Redirect naar de lijst van families
        } else {
            $family = $this->familyModel->getFamilyById($id); // Haal de familie op
            include '../views/family/edit.php'; // Laad de weergave voor het bewerken van een familie
        }
    }

    // Verwijder een familie op basis van ID
    public function deleteFamily($id) {
        $this->familyModel->deleteFamily($id); // Verwijder de familie
        header('Location: ../public/index.php?action=listFamilies'); // Redirect naar de lijst van families
    }

    // Lijst alle leden van een specifieke familie
    public function listFamilyMembers($familyId) {
        $family = $this->familyModel->getFamilyById($familyId); // Haal de familie op
        $members = $this->memberModel->getAllMembersByFamily($familyId); // Haal de leden op
        include '../views/member/list.php'; // Laad de weergave voor de lijst van leden
    }

    // Toon het formulier voor het toevoegen van een nieuw gezinslid
    public function createFamilyMember($familyId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $dob = $_POST['dob'];
            $memberTypeId = $_POST['member_type_id'];
            $this->memberModel->addMember($familyId, $name, $dob, $memberTypeId); // Voeg het gezinslid toe
            header("Location: ../public/index.php?action=listFamilyMembers&familyId=$familyId"); // Redirect naar de ledenlijst van de familie
        } else {
            $memberTypes = $this->memberModel->getMemberTypes(); // Haal de lidtypes op
            include '../views/member/create.php'; // Laad de weergave voor het aanmaken van een gezinslid
        }
    }

    // Toon het formulier voor het bewerken van een bestaand gezinslid
    public function editFamilyMember($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $dob = $_POST['dob'];
            $memberTypeId = $_POST['member_type_id'];
            $this->memberModel->updateMember($id, $name, $dob, $memberTypeId); // Werk het gezinslid bij
            $familyId = $_POST['family_id'];
            header("Location: ../public/index.php?action=listFamilyMembers&familyId=$familyId"); // Redirect naar de ledenlijst van de familie
        } else {
            $member = $this->memberModel->getMemberById($id); // Haal het gezinslid op
            $memberTypes = $this->memberModel->getMemberTypes(); // Haal de lidtypes op
            include '../views/member/edit.php'; // Laad de weergave voor het bewerken van een gezinslid
        }
    }

    // Verwijder een gezinslid
    public function deleteFamilyMember($id, $familyId) {
        $this->memberModel->deleteMember($id); // Verwijder het gezinslid
        header("Location: ../public/index.php?action=listFamilyMembers&familyId=$familyId"); // Redirect naar de ledenlijst van de familie
    }

    // Lijst alle bijdragen voor een familie
    public function listContributions($familyId) {
        $family = $this->familyModel->getFamilyById($familyId); // Haal de familie op
        $contributions = $this->contributionModel->getContributionsByFamily($familyId); // Haal de bijdragen op
        include '../views/contribution/list.php'; // Laad de weergave voor de lijst van bijdragen
    }

    // Toon het formulier voor het toevoegen van een nieuwe bijdrage
    public function createContribution($familyId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $memberId = $_POST['member_id'];
            $age = $this->memberModel->calculateAge($_POST['dob']); // Bereken de leeftijd
            $memberTypeId = $_POST['member_type_id'];
            $fiscalYear = $_POST['fiscal_year'];
            $amount = $this->contributionModel->calculateContribution($age, $memberTypeId); // Bereken het bedrag van de bijdrage
            $this->contributionModel->addContribution($familyId, $memberId, $age, $memberTypeId, $amount, $fiscalYear); // Voeg de bijdrage toe
            header("Location: ../public/index.php?action=listContributions&familyId=$familyId"); // Redirect naar de lijst van bijdragen
        } else {
            $members = $this->memberModel->getAllMembersByFamily($familyId); // Haal de leden van de familie op
            include '../views/contribution/create.php'; // Laad de weergave voor het aanmaken van een bijdrage
        }
    }

    // Toon het formulier voor het bewerken van een bestaande bijdrage
    public function editContribution($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $familyId = $_POST['family_id'];
            $age = $this->memberModel->calculateAge($_POST['dob']); // Bereken de leeftijd
            $memberTypeId = $_POST['member_type_id'];
            $fiscalYear = $_POST['fiscal_year'];
            $amount = $this->contributionModel->calculateContribution($age, $memberTypeId); // Bereken het bedrag van de bijdrage
            $this->contributionModel->updateContribution($id, $age, $memberTypeId, $amount, $fiscalYear); // Werk de bijdrage bij
            header("Location: ../public/index.php?action=listContributions&familyId=$familyId"); // Redirect naar de lijst van bijdragen
        } else {
            $contribution = $this->contributionModel->getContributionById($id); // Haal de bijdrage op
            $members = $this->memberModel->getAllMembersByFamily($contribution['family_id']); // Haal de leden van de familie op
            include '../views/contribution/edit.php'; // Laad de weergave voor het bewerken van een bijdrage
        }
    }

    // Verwijder een bijdrage
    public function deleteContribution($id, $familyId) {
        $this->contributionModel->deleteContribution($id); // Verwijder de bijdrage
        header("Location: ../public/index.php?action=listContributions&familyId=$familyId"); // Redirect naar de lijst van bijdragen
    }
}
?>
