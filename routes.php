<?php
require_once './controllers/AuthController.php';
require_once './controllers/InternshipController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'register':
                (new AuthController())->register($_POST);
                break;
            case 'login':
                (new AuthController())->login($_POST);
                break;
            case 'post_internship':
                (new InternshipController())->create($_POST);
                break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'list_internships') {
        (new InternshipController())->listAll();
    }
}
