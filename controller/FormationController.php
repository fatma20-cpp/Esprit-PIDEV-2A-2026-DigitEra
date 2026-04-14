<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Formation.php';

class FormationController {

    private $formation;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();
        $this->formation = new Formation($db);
    }

    // ➕ CREATE
    public function addFormation($data) {
        $this->formation->titre = $data['titre'];
        $this->formation->description = $data['description'];
        $this->formation->domaine = $data['domaine'];
        $this->formation->niveau = $data['niveau'];

        return $this->formation->create();
    }
    public function getFormationById($id){
        return $this->formation->readOne($id);
    }
    // 📄 READ
    public function getFormations() {
        return $this->formation->read();
    }

    // ✏ UPDATE
    public function updateFormation($data) {
        $this->formation->id = $data['id'];
        $this->formation->titre = $data['titre'];
        $this->formation->description = $data['description'];
        $this->formation->domaine = $data['domaine'];
        $this->formation->niveau = $data['niveau'];

        return $this->formation->update();
    }

    // ❌ DELETE
    public function deleteFormation($id) {
        $this->formation->id = $id;
        return $this->formation->delete();
    }

}
?>