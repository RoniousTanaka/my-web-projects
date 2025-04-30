<?php
require_once './config/database.php';

class Application {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Apply for an internship
    public function submit($data) {
        $stmt = $this->conn->prepare("INSERT INTO applications (user_id, internship_id, cover_letter) VALUES (?, ?, ?)");
        return $stmt->execute([$data['user_id'], $data['internship_id'], $data['cover_letter']]);
    }

    // Get all applications for a user
    public function getByUser($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM applications WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all applications for admin to review
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM applications");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update application status (approved or rejected)
    public function updateStatus($applicationId, $status) {
        $stmt = $this->conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $applicationId]);
    }
}
