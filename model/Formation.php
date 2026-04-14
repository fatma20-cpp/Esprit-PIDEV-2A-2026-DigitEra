<?php

class Formation {

    private $conn;
    private $table = "formation";

    public $id;
    public $titre;
    public $description;
    public $domaine;
    public $niveau;
    public $prix;
    public $duree;
    public $instructor;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 🔍 READ ONE
    public function readOne($id){
        $query = "SELECT * FROM formation WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ➕ CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
        (titre, description, domaine, niveau, prix, duree, instructor, date_creation)
        VALUES (:titre, :description, :domaine, :niveau, :prix, :duree, :instructor, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':domaine', $this->domaine);
        $stmt->bindParam(':niveau', $this->niveau);
        $stmt->bindParam(':prix', $this->prix);
        $stmt->bindParam(':duree', $this->duree);
        $stmt->bindParam(':instructor', $this->instructor);

        return $stmt->execute();
    }

    // 📄 READ ALL
    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✏ UPDATE
    public function update() {
        $query = "UPDATE " . $this->table . " 
        SET titre=:titre, description=:description, domaine=:domaine, niveau=:niveau,
            prix=:prix, duree=:duree, instructor=:instructor
        WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':domaine', $this->domaine);
        $stmt->bindParam(':niveau', $this->niveau);
        $stmt->bindParam(':prix', $this->prix);
        $stmt->bindParam(':duree', $this->duree);
        $stmt->bindParam(':instructor', $this->instructor);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // ❌ DELETE
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

}
?>