<?php

class Reclamation {
    private $conn;
    private $table = "reclamation";

    public $id_reclamation;
    public $nom;
    public $prenom;
    public $id_client;
    public $email;
    public $sujet;
    public $type_probleme;
    public $message;
    public $reponse;
    public $date_reponse;

    public function __construct($db) {
        $this->conn = $db;
    }

   public function read(){
    $query = "SELECT * FROM reclamation";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // ✅ CORRECTION
}

   public function readByClientId($clientId){
    $query = "SELECT * FROM reclamation WHERE id_client = ? ORDER BY id_reclamation DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$clientId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

  public function getById($id) {
    $query = "SELECT * FROM $this->table WHERE id_reclamation = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

  public function getByIdAndClientId($id, $clientId) {
    $query = "SELECT * FROM $this->table WHERE id_reclamation = ? AND id_client = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$id, $clientId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

  public function checkClientIdExists($clientId) {
    $query = "SELECT COUNT(*) as count FROM $this->table WHERE id_client = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$clientId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

  public function generateClientId() {
    // 🔹 Générer un ID client unique (8 chiffres)
    do {
        $clientId = str_pad(rand(10000000, 99999999), 8, "0", STR_PAD_LEFT);
    } while ($this->checkClientIdExists($clientId));
    return $clientId;
  }

    public function update() {
        $sql = "UPDATE $this->table 
                SET sujet = :sujet, message = :message, type_probleme = :type 
                WHERE id_reclamation = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':sujet' => $this->sujet,
            ':message' => $this->message,
            ':type' => $this->type_probleme,
            ':id' => $this->id_reclamation
        ]);
    }
    public function create() {
    // 🔹 Générer un ID unique au format N°XXXXXX (basé sur un compteur)
    $queryCount = "SELECT COUNT(*) as count FROM $this->table";
    $stmt = $this->conn->prepare($queryCount);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $result['count'] + 1;
    
    $this->id_reclamation = "N°" . str_pad($count, 6, "0", STR_PAD_LEFT);
    
    $query = "INSERT INTO $this->table 
              (id_reclamation, id_client, nom, prenom, email, sujet, type_probleme, message) 
              VALUES (:id, :client_id, :nom, :prenom, :email, :sujet, :type, :message)";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute([
        ':id' => $this->id_reclamation,
        ':client_id' => $this->id_client,
        ':nom' => $this->nom,
        ':prenom' => $this->prenom,
        ':email' => $this->email,
        ':sujet' => $this->sujet,
        ':type' => $this->type_probleme,
        ':message' => $this->message
    ]);
}

    public function delete($id) {
    $query = "DELETE FROM $this->table WHERE id_reclamation = ?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$id]);
}

    // 🔹 Enregistrer la réponse à une réclamation
    public function saveResponse($id, $reponse) {
        // 🔹 Vérifier si la colonne 'reponse' existe, sinon la créer
        try {
            $checkColumn = "SHOW COLUMNS FROM $this->table LIKE 'reponse'";
            $stmt = $this->conn->prepare($checkColumn);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                // La colonne n'existe pas, la créer
                $alterTable = "ALTER TABLE $this->table ADD COLUMN reponse TEXT NULL, ADD COLUMN date_reponse DATETIME NULL";
                $this->conn->exec($alterTable);
            }
        } catch (Exception $e) {
            // La colonne existe déjà
        }

        // 🔹 Mettre à jour la réponse
        $query = "UPDATE $this->table SET reponse = :reponse, date_reponse = NOW() WHERE id_reclamation = :id";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            ':reponse' => $reponse,
            ':id' => $id
        ]);
    }

}