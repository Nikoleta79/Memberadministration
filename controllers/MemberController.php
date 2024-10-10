<?php
// controllers/MemberController.php

namespace App\Controllers;

use App\Models\Family;
use App\Models\Member;
use App\Models\MemberType;

// Vereiste modelbestanden
require_once '../models/Member.php';
require_once '../models/Family.php';
require_once '../models/MemberType.php';

class MemberController {
    private $memberModel; // Model voor leden
    private $familyModel; // Model voor families
    private $memberTypeModel; // Model voor lidtypen

    public function __construct() {
        $this->memberModel = new Member();
        $this->familyModel = new Family();
        $this->memberTypeModel = new MemberType();
    }

    // Toon alle leden
    public function index() {
        $members = $this->memberModel->getAllMembers(); // Verkrijg alle leden
        require '../views/member/index.php'; // Laad de weergave voor leden
    }

    // Toon alle gezinsleden voor een gegeven gezin
    public function listFamilyMembers($familyId) {
        $members = $this->memberModel->getAllMembersByFamily($familyId); // Verkrijg gezinsleden
        $family = $this->familyModel->getFamilyById($familyId); // Verkrijg gezin
        require '../views/member/list.php'; // Laad de weergave voor gezinsleden
    }

    // Toon het formulier om een nieuw gezinslid aan te maken
    public function createFamilyMember($familyId) { 
        $families = $this->familyModel->getAllFamilies(); // Verkrijg alle gezinnen
        $memberTypes = $this->memberTypeModel->getAllMemberTypes(); // Verkrijg alle lidtypen
        require '../views/member/create.php'; // Laad de weergave voor het aanmaken van gezinsleden
    }

    // Maak een nieuw gezinslid aan
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $family_id = $_GET['familyId']; // Verkrijg het gezin ID
            $name = $_POST['name']; // Verkrijg de naam
            $dob = $_POST['dob']; // Verkrijg de geboortedatum
            $member_type_id = $_POST['member_type_id']; // Verkrijg het lidtype ID

            // Valideer de gegevens
            if (empty(trim($name)) || empty(trim($dob)) || empty(trim($member_type_id))) {
                echo "Alle velden zijn verplicht."; // Geef een foutmelding weer
                return; // Stop de functie
            }

            // Maak het lid aan
            $this->memberModel->addMember($family_id, $name, $dob, $member_type_id);
            header('Location: ../public/index.php?action=listFamilyMembers&familyId=' . $family_id); // Redirect naar gezinsledenlijst
            exit;
        } else {
            // Corrigeer de methodeaanroep
            $this->createFamilyMember($_GET['familyId']);
        }
    }

    // Toon het formulier om een gezinslid te bewerken
    public function editFamilyMember($id) { 
        $member = $this->memberModel->getMemberById($id); // Verkrijg het lid
        $families = $this->familyModel->getAllFamilies(); // Verkrijg alle gezinnen
        $memberTypes = $this->memberTypeModel->getAllMemberTypes(); // Verkrijg alle lidtypen
        require '../views/member/edit.php'; // Laad de weergave voor het bewerken van gezinsleden
    }

    // Bewerk een gezinslid
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name']; // Verkrijg de naam
            $dob = $_POST['dob']; // Verkrijg de geboortedatum
            $member_type_id = $_POST['member_type_id']; // Verkrijg het lidtype ID

            // Valideer de gegevens
            if (empty(trim($name)) || empty(trim($dob)) || empty(trim($member_type_id))) {
                echo "Alle velden zijn verplicht."; // Geef een foutmelding weer
                return; // Stop de functie
            }

            $this->memberModel->updateMember($id, $name, $dob, $member_type_id); // Werk het lid bij
            header('Location: ../public/index.php?action=listFamilyMembers&familyId=' . $_GET['familyId']); // Redirect naar gezinsledenlijst
            exit;
        } else {
            $this->editFamilyMember($id); // Toon het bewerkingsformulier
        }
    }
    
    // Verwijder een gezinslid
    public function deleteFamilyMember($id) {
        $familyId = $_GET['familyId']; // Verkrijg het gezin ID
        $this->memberModel->deleteMember($id); // Verwijder het lid
        header('Location: ../public/index.php?action=listFamilyMembers&familyId=' . $familyId); // Redirect naar gezinsledenlijst
        exit;
    }
}
?>

