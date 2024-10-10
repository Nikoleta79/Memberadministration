<?php
// Vereiste modelbestand voor de User klasse
require_once '../models/User.php';

class UserController {
    private $userModel; // Model voor gebruikersbeheer

    // Constructor om de User model instantie te initialiseren
    public function __construct() {
        $this->userModel = new User();
    }

    // Methode voor inloggen van de gebruiker
    public function login($username, $password) {
        // Roep de login methode aan van het User model
        return $this->userModel->login($username, $password);
    }
}
?>

