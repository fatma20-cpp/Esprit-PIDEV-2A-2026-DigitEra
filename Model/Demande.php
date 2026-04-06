<?php
class Demande {
    private $id;
    private $id_offre;
    private $nom;
    private $message;

    public function __construct($id, $id_offre, $nom, $message) {
        $this->id = $id;
        $this->id_offre = $id_offre;
        $this->nom = $nom;
        $this->message = $message;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getIdOffre() { return $this->id_offre; }
    public function getNom() { return $this->nom; }
    public function getMessage() { return $this->message; }

    // Setters
    public function setNom($nom) { $this->nom = $nom; }
    public function setMessage($message) { $this->message = $message; }
}
?>