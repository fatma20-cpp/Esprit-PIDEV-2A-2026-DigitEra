<?php
class Certificat {

    private $conn;
    private $table = "certificate";

    public $id;
    public $user_name;
    public $formation_id;
    public $certificate_code;
    public $date_obtention;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){

        $sql = "INSERT INTO " . $this->table . " 
                (user_name, formation_id, certificate_code, date_obtention) 
                VALUES (:user_name, :formation_id, :certificate_code, :date_obtention)";

        $query = $this->conn->prepare($sql);

        // 🔥 BETTER CODE (unique)
        $this->certificate_code = strtoupper(uniqid("CERT-"));

        // 🔐 CLEAN DATA
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));

        // 🔗 BIND VALUES
        $query->bindParam(':user_name', $this->user_name);
        $query->bindParam(':formation_id', $this->formation_id);
        $query->bindParam(':certificate_code', $this->certificate_code);
        $query->bindParam(':date_obtention', $this->date_obtention);

        return $query->execute();
    }
    public function read(){
    $sql = "SELECT c.*, f.titre 
            FROM certificate c
            JOIN formation f ON c.formation_id = f.id";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function delete($id){
    $sql = "DELETE FROM certificate WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
public function existsByName($name){
    $sql = "SELECT COUNT(*) as total FROM certificate WHERE user_name = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$name]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['total'] > 0;
}
}
?>