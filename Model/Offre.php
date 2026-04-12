<?php
class Offre {

    private $id_offer = null;
    private $title = null;
    private $description = null;
    private $budget = null;

    private $created_at = null;
    private $category = null;
    private $status = null;
    private $deadline = null;   
    private $id_company = null;

    function __construct($title, $description, $budget, $id_company) {
        $this->title = $title;
        $this->description = $description;
        $this->budget = $budget;
        $this->id_company = $id_company;
    }

    // GETTERS
    public function getIdOffer() {
        return $this->id_offer;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getBudget() {
        return $this->budget;
    }

    public function getCompanyId() {
        return $this->id_company;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getCategory() {
        return $this->category;
    }

    private function getStatus() {
        return $this->status;
    }

    public function getDeadline() {
        return $this->deadline;
    }

    // SETTERS
    public function setIdOffer($id) {
        $this->id_offer = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setBudget($budget) {
        $this->budget = $budget;
    }

    

    public function setCompanyId($id_company) {
        $this->id_company = $id_company;
    }

    
    
        public function setCreatedAt($created_at) {
            $this->created_at = $created_at;
        }

    public function setCategory($category) {
        $this->category = $category;
       }
    public function setStatus($status) {
        $this->status = $status;
    }
    public function setDeadline($deadline) {
        $this->deadline = $deadline;
    }
}
?>