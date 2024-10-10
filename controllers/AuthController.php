<?php
require_once '../models/User.php';

class AuthController {
    public function login() {
        session_start(); // Start de sessie aan het begin

        // Initialiseer een foutmelding variabele
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verkrijg de gebruikersnaam en het wachtwoord van het formulier
            $username = trim($_POST['username']); // Verwijder eventuele witruimtes
            $password = trim($_POST['password']); // Verwijder eventuele witruimtes

            // Valideer of de gebruikersnaam en het wachtwoord zijn ingevuld
            if (empty($username) || empty($password)) {
                $error = 'Fill in your username and your password'; // Foutmelding als een van beide velden leeg is
            } else {
                // Maak een nieuw User-model aan
                $userModel = new User();
                
                // Probeer in te loggen met de opgegeven gebruikersnaam en wachtwoord
                if ($userModel->login($username, $password)) {
                    // Succesvolle login
                    header('Location: ../public/index.php?action=listFamilies');
                    exit;
                } else {
                    // Ongeldige inloggegevens
                    $error = 'Non valid password or name'; // Verbeterde foutmelding
                }
            }
        }

        // Inclusie van de loginweergave met eventuele foutmeldingen
        require '../views/auth/login.php';
    }

    public function logout() {
        session_start(); // Start de sessie
        session_destroy(); // Vernietig de sessie
        header('Location: ../public/index.php?action=login'); // Redirect naar de inlogpagina
        exit;
    }
}
?>


