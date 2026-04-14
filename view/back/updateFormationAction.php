<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();

if($_POST){

    // 🔐 ID check
    if(!isset($_POST['id']) || !is_numeric($_POST['id'])){
        die("ID invalide");
    }

    // 🔹 Titre
    if(strlen($_POST['titre']) < 3){
        die("Titre invalide");
    }

    // 🔹 Domaine
    if(empty($_POST['domaine'])){
        die("Domaine obligatoire");
    }

    // 🔹 Niveau
    if(empty($_POST['niveau'])){
        die("Niveau obligatoire");
    }

    // 🔹 Prix
    if(!isset($_POST['prix']) || $_POST['prix'] <= 0){
        die("Prix invalide");
    }

    // 🔹 Durée
    if(empty($_POST['duree'])){
        die("Durée obligatoire");
    }

    // 🔹 Instructor
    if(empty($_POST['instructor'])){
        die("Instructor obligatoire");
    }

    // 🚀 UPDATE
    $controller->updateFormation($_POST);

    header("Location: listFormation.php?success=updated");
    exit;
}
?>