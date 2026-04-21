<?php
include_once '../config.php';
include_once '../Model/Demande.php';

class DemandeController {

    // CREATE DEMANDE
    function ajouterDemande($demande){

    $sql = "INSERT INTO demandes 
            (id_offre, id_freelancer, price, delivery_time, message)
            VALUES (:id_offre, :id_freelancer, :price, :delivery_time, :message)";

    $db = config::getConnexion();

    $query = $db->prepare($sql);
    $query->execute([
        'id_offre' => $demande->getOfferId(),
        'id_freelancer' => $demande->getFreelancerId(),
        'price' => $demande->getPrice(),
        'delivery_time' => $demande->getDeliveryTime(),
        'message' => $demande->getMessage()
    ]);
    }   

    // 🔹 GET DEMANDES BY FREELANCER (WITH OFFER INFO)
    function getDemandesByFreelancer($id_freelancer){

    $sql = "SELECT d.*, o.title, o.category, o.budget
            FROM demandes d
            JOIN offres o ON d.id_offre = o.id_offre
            WHERE d.id_freelancer = :id_freelancer
            ORDER BY d.created_at DESC";

    $db = config::getConnexion();

    $query = $db->prepare($sql);
    $query->execute(['id_freelancer' => $id_freelancer]);

    return $query->fetchAll();
    }

    // GET DEMANDES BY OFFER
    function getDemandesByOffer($id_offre){

    $sql = "SELECT d.*, 
                   u.nom, 
                   u.prenom, 
                   u.email
            FROM demandes d
            JOIN users u ON d.id_freelancer = u.id_user
            WHERE d.id_offre = :id_offre
            ORDER BY d.created_at DESC";

    $db = config::getConnexion();

    $query = $db->prepare($sql);
    $query->execute(['id_offre' => $id_offre]);

    return $query->fetchAll();
    }

// Get all demandes (for admin)
    function getAllDemandes(){

    $sql = "SELECT d.*, 
                   o.title AS offer_title,
                   o.category,
                   o.budget,
                   u.nom,
                   u.prenom,
                   CONCAT(u.nom, ' ', u.prenom) AS freelancer_name,
                   u.email
            FROM demandes d
            JOIN offres o ON d.id_offre = o.id_offre
            JOIN users u ON d.id_freelancer = u.id_user
            ORDER BY d.created_at DESC";

    $db = config::getConnexion();
    $query = $db->prepare($sql);
    $query->execute();

    return $query->fetchAll();
    }

    // ACCEPT DEMANDE
   function accepterDemande($id_demande, $id_offre){

    $db = config::getConnexion();

    try {

        // 🔹 1. Accept this demande
        $sql1 = "UPDATE demandes 
                 SET status = 'accepted' 
                 WHERE id_demande = :id";
        $stmt1 = $db->prepare($sql1);
        $stmt1->execute(['id' => $id_demande]);

        // 🔹 2. Reject others
        $sql2 = "UPDATE demandes 
                 SET status = 'rejected'
                 WHERE id_offre = :id_offre 
                 AND id_demande != :id";
        $stmt2 = $db->prepare($sql2);
        $stmt2->execute([
            'id_offre' => $id_offre,
            'id' => $id_demande
        ]);

        // 🔹 3. Close offer
        $sql3 = "UPDATE offres 
                 SET status = 'closed' 
                 WHERE id_offre = :id_offre";
        $stmt3 = $db->prepare($sql3);
        $stmt3->execute(['id_offre' => $id_offre]);

    } catch (PDOException $e) {
        die("SQL Error: " . $e->getMessage());
    }
}
    // REJECT DEMANDE
    function refuserDemande($id_demande){
        $sql = "UPDATE demandes SET status = 'rejected' WHERE id_demande = :id";
        $db = config::getConnexion();
        $db->prepare($sql)->execute(['id' => $id_demande]);
    }
}
?>