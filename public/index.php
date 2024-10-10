<?php
// public/index.php

// Vereiste controllerbestanden
require_once '../controllers/FamilyController.php';
require_once '../controllers/MemberController.php';
require_once '../controllers/ContributionController.php';

// Gebruik statements voor namespaces
use App\Controllers\FamilyController;
use App\Controllers\MemberController;
use App\Controllers\ContributionController;

// Instantieer controllers
$familyController = new FamilyController();
$memberController = new MemberController();
$contributionController = new ContributionController();

// Routinglogica
$action = isset($_GET['action']) ? $_GET['action'] : null; // Verkrijg de actie uit de URL

switch ($action) {
    // Acties voor families
    case 'listFamilies':
        $familyController->listFamilies(); // Lijst alle families op
        break;

    case 'createFamily':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $familyController->createFamily(); // Maak een nieuwe familie aan
        } else {
            $familyController->createFamilyForm(); // Toon het aanmaakformulier voor een nieuwe familie
        }
        break;

    case 'editFamily':
        if (isset($_GET['id'])) {
            $familyId = $_GET['id']; // Verkrijg het ID van de familie
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $familyController->editFamily($familyId); // Werk de familie bij
            } else {
                $familyController->editFamily($familyId); // Toon het bewerkingsformulier
            }
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen ID is
            exit;
        }
        break;

    case 'deleteFamily':
        if (isset($_GET['id'])) {
            $familyId = $_GET['id']; // Verkrijg het ID van de familie
            $familyController->deleteFamily($familyId); // Verwijder de familie
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen ID is
            exit;
        }
        break;

    // Acties voor leden
    case 'listFamilyMembers':
        if (isset($_GET['familyId'])) {
            $familyId = $_GET['familyId']; // Verkrijg het ID van de familie
            $memberController->listFamilyMembers($familyId); // Lijst alle leden van de familie op
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen familie-ID is
            exit;
        }
        break;

    case 'createFamilyMember':
        if (isset($_GET['familyId'])) {
            $familyId = $_GET['familyId']; // Verkrijg het ID van de familie
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $memberController->create(); // Maak een nieuw familielid aan
            } else {
                $memberController->createFamilyMember($familyId); // Toon het aanmaakformulier voor een nieuw familielid
            }
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen familie-ID is
            exit;
        }
        break;

    case 'editFamilyMember':
        if (isset($_GET['id'])) {
            $memberId = $_GET['id']; // Verkrijg het ID van het lid
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $memberController->edit($memberId); // Werk het familielid bij
            } else {
                $memberController->editFamilyMember($memberId); // Toon het bewerkingsformulier
            }
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen ID is
            exit;
        }
        break;

    case 'deleteFamilyMember':
        if (isset($_GET['id']) && isset($_GET['familyId'])) {
            $memberId = $_GET['id']; // Verkrijg het ID van het lid
            $memberController->deleteFamilyMember($memberId); // Verwijder het familielid
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen ID is
            exit;
        }
        break;

    // Acties voor bijdragen
    case 'listContributions':
        if (isset($_GET['familyId'])) {
            $familyId = $_GET['familyId']; // Verkrijg het ID van de familie
            $contributionController->getAllContributions($familyId); // Lijst alle bijdragen van de familie op
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen familie-ID is
            exit;
        }
        break;

    case 'createContribution':
        if (isset($_GET['familyId'])) {
            $familyId = $_GET['familyId']; // Verkrijg het ID van de familie
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $contributionController->createContribution($familyId); // Maak een nieuwe bijdrage aan
            } else {
                $contributionController->createContribution($familyId); // Toon het aanmaakformulier voor een nieuwe bijdrage
            }
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen familie-ID is
            exit;
        }
        break;

    case 'editContribution':
        if (isset($_GET['id'])) {
            $contributionId = $_GET['id']; // Verkrijg het ID van de bijdrage
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $contributionController->edit($contributionId); // Werk de bijdrage bij
            } else {
                $contributionController->edit($contributionId); // Toon het bewerkingsformulier
            }
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen ID is
            exit;
        }
        break;

    case 'deleteContribution':
        if (isset($_GET['id']) && isset($_GET['familyId'])) {
            $contributionId = $_GET['id']; // Verkrijg het ID van de bijdrage
            $contributionController->delete($contributionId); // Verwijder de bijdrage
        } else {
            header('Location: index.php?action=listFamilies'); // Redirect als er geen ID is
            exit;
        }
        break;

    default:
        // Redirect naar lijst met families als standaardacties
        header('Location: index.php?action=listFamilies'); // Redirect naar de lijst met families
        exit;
}
?>

