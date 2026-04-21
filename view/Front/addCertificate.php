<?php
require_once '../../controller/CertificatController.php';

$controller = new CertificatController();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // 🔹 USER NAME
    if(empty($_POST['user_name'])){
        die("Nom obligatoire");
    }

    // 🔹 FORMATION ID
    if(empty($_POST['formation_id']) || !is_numeric($_POST['formation_id'])){
        die("Formation invalide");
    }

    // 🔹 DATE (manual control)
    if(empty($_POST['date_obtention'])){
        die("Date obligatoire");
    }

    // ❌ future date not allowed
    if($_POST['date_obtention'] > date("Y-m-d")){
        die("Date invalide (future interdite)");
    }

    // 🔥 GENERATE CERTIFICATE CODE
    $code = strtoupper(uniqid("CERT-"));

    // 🔥 ADD TO DATA ARRAY
    $_POST['certificate_code'] = $code;

    // 🚀 SAVE IN DATABASE
    $controller->addCertificat($_POST);

    // ✅ REDIRECT WITH DATA
    header("Location: index.php?action=showCert&user_name=".$_POST['user_name']."&date=".$_POST['date_obtention']."&code=".$code);
    exit;
}
?>  