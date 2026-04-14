<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();

if($_POST){

    // 🔹 Titre
    if(strlen($_POST['titre']) < 3){
        die("Titre invalide");
    }

    // 🔹 Domaine
    if(empty($_POST['domaine'])){
        die("Domaine obligatoire");
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

    // 🚀 ADD FORMATION
    $controller->addFormation($_POST);

    header("Location: listFormation.php?success=1");
    exit;
}
?>