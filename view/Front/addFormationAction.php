<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();

if($_POST){

    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $domaine = trim($_POST['domaine']);
    $niveau = $_POST['niveau'];
    $prix = $_POST['prix'];
    $duree = trim($_POST['duree']);
    $instructor = trim($_POST['instructor']);

    // 🔒 REGEX lettres seulement
    $regex = "/^[A-Za-zÀ-ÿ\s]+$/";

    // ❌ VALIDATION SERVER (IMPORTANT)
    if(
        empty($titre) ||
        empty($description) ||
        empty($domaine) ||
        empty($niveau) ||
        empty($prix) ||
        empty($duree) ||
        empty($instructor)
    ){
        die("Tous les champs sont obligatoires");
    }

    if(!preg_match($regex, $titre)){
        die("Titre invalide");
    }

    if(!preg_match($regex, $domaine)){
        die("Domaine invalide");
    }

    if(!preg_match($regex, $instructor)){
        die("Instructor invalide");
    }

    if($prix <= 0){
        die("Prix invalide");
    }

    // ✅ everything ok
    $controller->addFormation($_POST);

    header("Location: index.php?success=1");
    exit();
}
?>