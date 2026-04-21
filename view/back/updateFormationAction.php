<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // 🔐 ID check
    if(!isset($_POST['id']) || !is_numeric($_POST['id'])){
        header("Location: index.php?page=list&error=id");
        exit;
    }

    if(strlen($_POST['titre']) < 3){
        header("Location: index.php?page=edit&id=".$_POST['id']."&error=titre");
        exit;
    }

    if(empty($_POST['domaine'])){
        header("Location: index.php?page=edit&id=".$_POST['id']."&error=domaine");
        exit;
    }

    if(empty($_POST['niveau'])){
        header("Location: index.php?page=edit&id=".$_POST['id']."&error=niveau");
        exit;
    }

    if(!isset($_POST['prix']) || $_POST['prix'] <= 0){
        header("Location: index.php?page=edit&id=".$_POST['id']."&error=prix");
        exit;
    }

    if(empty($_POST['duree'])){
        header("Location: index.php?page=edit&id=".$_POST['id']."&error=duree");
        exit;
    }

    if(empty($_POST['instructor'])){
        header("Location: index.php?page=edit&id=".$_POST['id']."&error=instructor");
        exit;
    }

    // 🚀 UPDATE
    $controller->updateFormation($_POST);

    // ✅ SUCCESS REDIRECT (FIXED)
    header("Location: index.php?page=list");
    exit;
}
?>