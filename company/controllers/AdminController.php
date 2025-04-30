<?php
require_once './controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $auth = new AuthController();

        switch ($_POST['action']) {
            case 'register':
                $auth->register($_POST);
                break;
            case 'login':
                $auth->login($_POST);
                break;
        }
    }
}
