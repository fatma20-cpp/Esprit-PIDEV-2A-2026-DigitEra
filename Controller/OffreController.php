<?php
include_once '../config.php';
include_once '../Model/Offre.php';

class OffreController {

    // 🔹 READ ALL
    function afficheroffres(){
        $sql = "SELECT * FROM offres";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch(Exception $e){
            die('Erreur: '.$e->getMessage());
        }
    }

    // 🔹 DELETE
    function supprimerOffer($id){
        $sql = "DELETE FROM offres WHERE id_offre = :id";
        $db = config::getConnexion();

        try {
            $req = $db->prepare($sql);
            $req->bindValue(':id', $id);
            $req->execute();
        } catch(Exception $e){
            die('Erreur: '.$e->getMessage());
        }
    }

    // 🔹 CREATE
    function ajouterOffer($offer){
        $sql = "INSERT INTO offres (title, description, budget, id_company)
                VALUES (:title, :description, :budget, :id_company)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'title' => $offer->getTitle(),
                'description' => $offer->getDescription(),
                'budget' => $offer->getBudget(),
                'id_company' => $offer->getCompanyId()
            ]);
        } catch(Exception $e){
            echo 'Erreur: '.$e->getMessage();
        }
    }

    // 🔹 GET ONE
    function recupererOffer($id){
        $sql = "SELECT * FROM offres WHERE id_offre = $id";
        $db = config::getConnexion();

        try {
            $query = $db->query($sql);
            return $query->fetch();
        } catch(Exception $e){
            die('Erreur: '.$e->getMessage());
        }
    }

    // 🔹 UPDATE
    function modifierOffer($offer, $id){
        $sql = "UPDATE offres SET
                title = :title,
                description = :description,
                budget = :budget
                WHERE id_offre = :id";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'title' => $offer->getTitle(),
                'description' => $offer->getDescription(),
                'budget' => $offer->getBudget(),
                'id' => $id
            ]);
        } catch(Exception $e){
            echo 'Erreur: '.$e->getMessage();
        }
    }

    // 🔹 SEARCH
    function rechercherOffer($title){
        $sql = "SELECT * FROM offres WHERE title LIKE '%$title%'";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch(Exception $e){
            die('Erreur: '.$e->getMessage());
        }
    }

    // 🔹 SORT
    function afficherTri(){
        $sql = "SELECT * FROM offres ORDER BY budget";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch(Exception $e){
            die('Erreur: '.$e->getMessage());
        }
    }
}
?>