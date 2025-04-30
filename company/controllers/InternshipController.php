<?php
require_once './models/Internship.php';

class InternshipController {
    public function listAll() {
        $internship = new Internship();
        return $internship->getAll();
    }

    public function create($data) {
        $internship = new Internship();
        return $internship->create($data);
    }
}
