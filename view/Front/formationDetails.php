<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();

if(!isset($_GET['id'])){
    die("Formation introuvable");
}

$formation = $controller->getFormationById($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Détails Formation</title>

<link rel="stylesheet" href="../back/template/css/templatemo-daynight-style.css">

<style>

/* 🌍 SAME STYLE AS INDEX */
body {
    background: #f5f7fb;
    font-family: Arial, sans-serif;
    margin: 0;
}

/* 🔝 NAVBAR SAME AS INDEX */
.top-nav {
    background: #FFFFFF;
    padding: 15px 30px;
}

.nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    color: #096996;
    font-size: 22px;
    font-weight: bold;
    text-decoration: none;
}

.nav-link {
    color: #0d2b35;
    margin-left: 20px;
    text-decoration: none;
    font-weight: 500;
}

.nav-link:hover {
    color: #0EA5E9;
}

/* 📄 CONTENT */
.main-content {
    padding: 30px;
}

/* 📦 SAME CARD STYLE */
.card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    max-width: 700px;
    margin: auto;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border-left: 5px solid #fba01c;
}

/* 🔘 BUTTON SAME */
.btn {
    display: inline-block;
    margin-top: 15px;
    padding: 8px 15px;
    background: #38BDF8;
    color: white;
    border-radius: 8px;
    text-decoration: none;
}

.btn:hover {
    background: #0EA5E9;
}

/* SUCCESS MESSAGE */
.success {
    background:#d7e8da;
    color:#096996;
    padding:10px;
    border-radius:10px;
    text-align:center;
    margin-bottom:20px;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="app-container">

<!-- 🔝 NAVBAR -->
<nav class="top-nav">
    <div class="nav-container">
        <a href="index.php" class="logo">My Platform</a>

        <div>
            <a href="index.php" class="nav-link">Home</a>
        </div>
    </div>
</nav>

<!-- 📄 CONTENT -->
<div class="main-content">

    <?php if(isset($_GET['booked'])): ?>
        <div class="success">
            Formation réservée avec succès ✔
        </div>
    <?php endif; ?>

    <div class="card">

        <h2 style="color:#096996;"><?php echo $formation['titre']; ?></h2>

        <p><?php echo $formation['description']; ?></p>

        <p><strong>Domaine:</strong> <?php echo $formation['domaine']; ?></p>

        <p><strong>Niveau:</strong> <?php echo $formation['niveau']; ?></p>

        <!-- BUTTONS -->
        <a href="index.php" class="btn">⬅ Retour</a>

        <a href="formationDetails.php?id=<?php echo $formation['id']; ?>&booked=1" class="btn">
            Book
        </a>

    </div>

</div>

</div>

<script src="../back/template/js/templatemo-daynight-script.js"></script>

</body>
</html>