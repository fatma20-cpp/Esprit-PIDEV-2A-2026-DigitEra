<?php
include_once '../config.php';
include_once '../Model/Demande.php';

class DemandeController {

    // CREATE DEMANDE
    function ajouterDemande($demande){

    $sql = "INSERT INTO demandes 
            (id_offre, id_freelancer, price, delivery_time, message)
            VALUES (:id_offer, :id_freelancer, :price, :delivery_time, :message)";

    $db = config::getConnexion();

    $query = $db->prepare($sql);
    $query->execute([
        'id_offer' => $demande->getOfferId(),
        'id_freelancer' => $demande->getFreelancerId(),
        'price' => $demande->getPrice(),
        'delivery_time' => $demande->getDeliveryTime(),
        'message' => $demande->getMessage()
    ]);
    }   

    // GET DEMANDES BY OFFER
    function getDemandesByOffer($id_offer){
        $sql = "SELECT * FROM demandes WHERE id_offer = :id_offer";
        $db = config::getConnexion();

        $query = $db->prepare($sql);
        $query->execute(['id_offer' => $id_offer]);

        return $query->fetchAll();
    }

    // ACCEPT DEMANDE
    function accepterDemande($id_demande, $id_offer){

        $db = config::getConnexion();

        // 1. Accept this demande
        $sql = "UPDATE demandes SET status = 'accepted' WHERE id_demande = :id";
        $db->prepare($sql)->execute(['id' => $id_demande]);

        // 2. Reject others
        $sql = "UPDATE demandes SET status = 'rejected'
                WHERE id_offer = :id_offer AND id_demande != :id";
        $db->prepare($sql)->execute([
            'id_offer' => $id_offer,
            'id' => $id_demande
        ]);

        // 3. CLOSE OFFER
        $sql = "UPDATE offres SET status = 'closed' WHERE id_offre = :id_offer";
        $db->prepare($sql)->execute(['id_offer' => $id_offer]);
    }

    // REJECT DEMANDE
    function refuserDemande($id_demande){
        $sql = "UPDATE demandes SET status = 'rejected' WHERE id_demande = :id";
        $db = config::getConnexion();
        $db->prepare($sql)->execute(['id' => $id_demande]);
    }
}
?>