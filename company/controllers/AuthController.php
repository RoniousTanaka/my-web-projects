<?php
require_once './models/User.php';

class AuthController {
    public function register($postData) {
        $user = new User();
        if ($user->register($postData)) {
            echo "Registration successful!";
        } else {
            echo "Registration failed!";
        }
    }

    public function login($postData) {
        $user = new User();
        $result = $user->login($postData['email'], $postData['password']);
        if ($result) {
            session_start();
            $_SESSION['user'] = $result;
            echo "Login successful! Welcome " . $result['name'];
        } else {
            echo "Login failed. Please check your credentials.";
        }
    }
}

