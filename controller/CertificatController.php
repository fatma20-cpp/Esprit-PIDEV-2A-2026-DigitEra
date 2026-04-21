<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Certificat.php';

class CertificatController {

    private $certificat;

    public function __construct(){
        $database = new Database();
        $db = $database->connect();
        $this->certificat = new Certificat($db);
    }

    public function addCertificat($data){

        // 🔹 USER DATA
        $this->certificat->user_name = $data['user_name'];
        $this->certificat->formation_id = $data['formation_id'];

        // 🔹 DATE (controle de saisie)
        $this->certificat->date_obtention = $data['date_obtention'];

        // 🔥 OPTIONAL: pass code if already generated
        if(isset($data['certificate_code'])){
            $this->certificat->certificate_code = $data['certificate_code'];
        }

        return $this->certificat->create();
    }
    public function getCertificats(){
    return $this->certificat->read();
}
public function deleteCertificat($id){
    return $this->certificat->delete($id);
}
public function certificatExists($name){
    return $this->certificat->existsByName($name);
}
}
?>