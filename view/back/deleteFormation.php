<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();

// 🔐 Check if ID exists and is valid
if(isset($_GET['id']) && is_numeric($_GET['id'])){

    $id = intval($_GET['id']);

    $controller->deleteFormation($id);

    header("Location: listFormation.php?success=deleted");
    exit;
}

// ❌ If no ID → redirect safely
header("Location: listFormation.php?error=1");
exit;
?>