<?php
class Offre {
    private $id;
    private $titre;
    private $description;
    private $prix;

    public function __construct($id, $titre, $description, $prix) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->prix = $prix;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getPrix() { return $this->prix; }

    // Setters
    public function setTitre($titre) { $this->titre = $titre; }
    public function setDescription($description) { $this->description = $description; }
    public function setPrix($prix) { $this->prix = $prix; }
}
?>