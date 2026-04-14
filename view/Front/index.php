<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();
$formations = $controller->getFormations();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Formations</title>

<script>
    if (localStorage.getItem('daynight-theme') === 'carbon') {
        document.documentElement.classList.add('carbon');
    }
</script>

<link rel="stylesheet" href="../back/template/templatemo-daynight-style.css">

<style>

/* 🌍 GLOBAL */
body {
    background: #f5f7fb;
    font-family: Arial, sans-serif;
    margin: 0;
}

/* 🔝 NAVBAR */
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

h1 {
    color: #0d2b35;
}

/* 🔍 FILTERS */
.filters {
    display: flex;
    gap: 10px;
    margin: 20px 0;
}

.filters input,
.filters select {
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #ccc;
    background: white;
}

/* 🧩 GRID */
.formations-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

/* 📦 CARD */
.card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border-left: 5px solid #fba01c;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card h3 {
    color: #096996;
}

.card p {
    color: #555;
}

/* 🔘 BUTTON */
.btn {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 15px;
    background: #38BDF8;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s;
}

.btn:hover {
    background: #0EA5E9;
}
.success {
    background:#d7e8da;
    color:#096996;
    padding:10px;
    border-radius:10px;
    text-align:center;
    margin-bottom:20px;
    font-weight:bold;
}

/* 📱 RESPONSIVE */
@media (max-width: 768px) {
    .formations-grid {
        grid-template-columns: 1fr;
    }
}

</style>
</head>

<body>

<div class="app-container">

    <!-- 🔝 NAVBAR -->
    <nav class="top-nav">
        <div class="nav-container">
            <div class="nav-left">
                <a href="index.php" class="logo">
                    <div class="logo-icon">✔</div>
                    Formation Certification
                </a>
                <div class="nav-menu">
                    <div class="nav-item">
                        <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                            Accueil
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="addFormation.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'addFormation.php' ? 'active' : ''; ?>">
                            Ajouter Formation
                        </a>
                    </div>
                </div>
            </div>
            <div class="nav-right">
                <div class="theme-toggle">
                    <button class="theme-btn theme-btn-snow active" onclick="setTheme('snow')" title="Snow Edition">☀</button>
                    <button class="theme-btn theme-btn-carbon" onclick="setTheme('carbon')" title="Carbon Edition">🌙</button>
                </div>
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

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Formations</h1>

        <a href="addFormation.php" class="btn">
            + Ajouter Formation
        </a>
    </div>

        <!-- 🔍 FILTER -->
        <div class="filters">
            <input type="text" id="search" placeholder="Rechercher...">

            <select id="domaine">
                <option value="">Tous domaines</option>
                <option value="design">Design</option>
                <option value="dev">Dev</option>
            </select>

            <select id="niveau">
                <option value="">Tous niveaux</option>
                <option value="debutant">Débutant</option>
                <option value="avance">Avancé</option>
            </select>
        </div>

        <!-- 🧩 GRID -->
        <div class="formations-grid" id="formationsContainer">

            <?php foreach($formations as $f): ?>

                <div class="card formation-item"
                     data-titre="<?php echo strtolower($f['titre']); ?>"
                     data-domaine="<?php echo strtolower($f['domaine']); ?>"
                     data-niveau="<?php echo strtolower($f['niveau']); ?>">

                    <h3><?php echo $f['titre']; ?></h3>

                    <p><?php echo substr($f['description'], 0, 80); ?>...</p>

                    <p><strong><?php echo $f['domaine']; ?></strong></p>

                    <p><?php echo $f['niveau']; ?></p>

                    <a href="formationDetails.php?id=<?php echo $f['id']; ?>" class="btn">
                        Voir plus →
                    </a>
                    <a href="index.php?booked=<?php echo $f['id']; ?>" class="btn">Book</a>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>

<!-- 🔍 FILTER SCRIPT -->
<script>
let searchInput = document.getElementById("search");
let domaineSelect = document.getElementById("domaine");
let niveauSelect = document.getElementById("niveau");

function filter() {
    let search = searchInput.value.toLowerCase();
    let domaine = domaineSelect.value;
    let niveau = niveauSelect.value;

    let items = document.querySelectorAll(".formation-item");

    items.forEach(item => {
        let titre = item.getAttribute("data-titre");
        let dom = item.getAttribute("data-domaine");
        let niv = item.getAttribute("data-niveau");

        let show = true;

        if (!titre.includes(search)) show = false;
        if (domaine && dom !== domaine) show = false;
        if (niveau && niv !== niveau) show = false;

        item.style.display = show ? "block" : "none";
    });
}

searchInput.addEventListener("keyup", filter);
domaineSelect.addEventListener("change", filter);
niveauSelect.addEventListener("change", filter);
</script>

<script src="../back/template/templatemo-daynight-script.js"></script>

</body>
</html>