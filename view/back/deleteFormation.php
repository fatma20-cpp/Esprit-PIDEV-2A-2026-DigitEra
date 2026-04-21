<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();

// Check if ID exists and is valid
if(isset($_GET['id']) && is_numeric($_GET['id'])){

    $id = intval($_GET['id']);

    // Delete formation
    $controller->deleteFormation($id);

    // Redirect to list page (BACK OFFICE)
    header("Location: index.php?page=list&success=deleted");
    exit;
}

// If error
header("Location: index.php?page=list&error=1");
exit;
?>