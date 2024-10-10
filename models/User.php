<?php
require_once '../config/config.php';

class User {
    private $db;

    public function __construct() {
        $this->db = getDB(); // Verkrijg de DB-verbinding
    }

    // Functie voor gebruikerslogin
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username"); // Bereid de SQL-query voor
        $stmt->execute(['username' => $username]); // Voer de query uit met de gebruikersnaam
        $user = $stmt->fetch(); // Haal de gebruiker op

        if ($user && password_verify($password, $user['password'])) { // Controleer of het wachtwoord correct is
            session_start(); // Start de sessie
            $_SESSION['user_id'] = $user['id']; // Sla gebruikers-ID op in de sessie
            $_SESSION['username'] = $user['username']; // Sla gebruikersnaam op in de sessie
            $_SESSION['role'] = $user['role']; // Sla rol van gebruiker op in de sessie
            return true; // Login succesvol
        }
        return false; // Login mislukt
    }

    // Functie voor gebruikersregistratie
    public function register($username, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash het wachtwoord
        $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)"); // Bereid de SQL-query voor
        return $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'role' => $role]); // Voer de query uit en retourneer het resultaat
    }

    // Haal alle gebruikers op voor beheersdoeleinden
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM users"); // Bereid de SQL-query voor
        return $stmt->fetchAll(); // Geef alle gebruikers terug
    }

    // Haal gebruiker op op basis van ID
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id"); // Bereid de SQL-query voor
        $stmt->execute(['id' => $id]); // Voer de query uit
        return $stmt->fetch(); // Geef het resultaat terug
    }

    // Werk gebruikersgegevens bij (voor beheer of gebruikersprofielbeheer)
    public function updateUser($id, $username, $role) {
        $stmt = $this->db->prepare("UPDATE users SET username = :username, role = :role WHERE id = :id"); // Bereid de SQL-query voor
        return $stmt->execute(['id' => $id, 'username' => $username, 'role' => $role]); // Voer de query uit en retourneer het resultaat
    }

    // Wijzig gebruikerswachtwoord
    public function changePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash het nieuwe wachtwoord
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id"); // Bereid de SQL-query voor
        return $stmt->execute(['id' => $id, 'password' => $hashedPassword]); // Voer de query uit en retourneer het resultaat
    }

    // Verwijder gebruiker
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id"); // Bereid de SQL-query voor
        return $stmt->execute(['id' => $id]); // Voer de query uit en retourneer het resultaat
    }

    // Haal rol van gebruiker op op basis van ID
    public function getUserRole($id) {
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = :id"); // Bereid de SQL-query voor
        $stmt->execute(['id' => $id]); // Voer de query uit
        return $stmt->fetchColumn(); // Geef de rol terug
    }

    // Controleer of een gebruikersnaam uniek is tijdens registratie
    public function isUsernameUnique($username) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = :username"); // Bereid de SQL-query voor
        $stmt->execute(['username' => $username]); // Voer de query uit
        return $stmt->fetchColumn() == 0; // Controleer of de gebruikersnaam uniek is
    }
}
?>

