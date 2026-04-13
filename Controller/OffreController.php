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

        function afficherOffreParCompanyId($id){
        $sql = "SELECT * FROM offres where id_company = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
             $query->execute(['id' => $id]);
            return $query->fetchAll();
        } catch(Exception $e){
            die('Erreur: '.$e->getMessage());
        }
    }

    

    // 🔹 DELETE (FIXED)
    function supprimerOffer($id){
        $sql = "DELETE FROM offres WHERE id_offre = :id";
        $db = config::getConnexion();

        try {
            $req = $db->prepare($sql);
            $req->bindValue(':id', (int)$id, PDO::PARAM_INT);
            return $req->execute();
        } catch(Exception $e){
            die('Erreur DELETE: '.$e->getMessage());
        }
    }

    // 🔹 CREATE
    function ajouterOffer($offer){
        $sql = "INSERT INTO offres (title, description, budget, id_company, deadline, category)
                VALUES (:title, :description, :budget, :id_company, :deadline, :category)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'title' => $offer->getTitle(),
                'description' => $offer->getDescription(),
                'budget' => $offer->getBudget(),
                'id_company' => $offer->getCompanyId(),
                'deadline' => $offer->getDeadline(),
                'category' => $offer->getCategory()
            ]);
        } catch(Exception $e){
            die('Erreur INSERT: '.$e->getMessage());
        }
    }

    // 🔹 GET ONE (SECURED)
    function recupererOffer($id){
        $sql = "SELECT * FROM offres WHERE id_offre = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch();
        } catch(Exception $e){
            die('Erreur: '.$e->getMessage());
        }
    }

    // 🔹 UPDATE (FIXED BUG)
    function modifierOffer($offer, $id){
        $sql = "UPDATE offres SET
                title = :title,
                description = :description,
                budget = :budget,
                deadline = :deadline,
                category = :category
                WHERE id_offre = :id";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'title' => $offer->getTitle(),
                'description' => $offer->getDescription(),
                'budget' => $offer->getBudget(),
                'deadline' => $offer->getDeadline(),
                'category' => $offer->getCategory(),
                'id' => $id
            ]);
        } catch(Exception $e){
            die('Erreur UPDATE: '.$e->getMessage());
        }
    }

    // 🔹 SEARCH
    function rechercherOffer($title){
        $sql = "SELECT * FROM offres WHERE title LIKE :title";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute(['title' => "%$title%"]);
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