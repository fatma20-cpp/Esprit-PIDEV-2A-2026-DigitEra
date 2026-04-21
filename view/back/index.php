<?php

require_once '../../controller/FormationController.php';
require_once '../../controller/CertificatController.php';

// CONTROLLERS
$controller = new FormationController();
$certController = new CertificatController();

// DATA
$formations = $controller->getFormations();
$certificates = $certController->getCertificats();

// ROUTING
$page = $_GET['page'] ?? 'dashboard';
$view = $_GET['action'] ?? '';

// 🔥 DELETE FORMATION
if($page == 'delete' && isset($_GET['id'])){
    $controller->deleteFormation($_GET['id']);
    header("Location: index.php?page=list");
    exit;
}

// 🔥 DELETE CERTIFICATE
if($page == 'deleteCert' && isset($_GET['id'])){
    $certController->deleteCertificat($_GET['id']);
    header("Location: index.php?page=certificates");
    exit;
}

// 🔥 DB CONNECTION
$pdo = new PDO("mysql:host=localhost;dbname=gestion_formation;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 🔥 UPDATE CERTIFICATE
if($view == 'updateCert'){
    $stmt = $pdo->prepare("
        UPDATE certificate 
        SET user_name=?, date_obtention=? 
        WHERE certificate_code=?
    ");
    $stmt->execute([
        $_POST['user_name'],
        $_POST['date_obtention'],
        $_POST['certificate_code']
    ]);

    header("Location: index.php?action=showCert&user_name=".$_POST['user_name']."&date=".$_POST['date_obtention']."&code=".$_POST['certificate_code']);
    exit;
}

// 🔥 GENERATE CERTIFICATE
if($view == 'generateCert'){
    $code = "CERT-" . rand(1000,9999);

    $stmt = $pdo->prepare("
        INSERT INTO certificate (user_name, formation_id, date_obtention, certificate_code)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['user_name'],
        $_POST['formation_id'],
        $_POST['date_obtention'],
        $code
    ]);

    header("Location: index.php?action=showCert&user_name=".$_POST['user_name']."&date=".$_POST['date_obtention']."&code=".$code);
    exit;
}

// 📊 CALCULATIONS
$debutant = $intermediaire = $avance = $revenu = 0;

foreach($formations as $f){
    if($f['niveau']=="debutant") $debutant++;
    if($f['niveau']=="intermediaire") $intermediaire++;
    if($f['niveau']=="avance") $avance++;
    $revenu += floatval($f['prix']);
}

$total = count($formations);
// 🔥 ADD FORMATION (MOVE HERE)
$errors = [];

if($page == 'add' && $_SERVER["REQUEST_METHOD"] == "POST"){

    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $domaine = trim($_POST['domaine']);
    $niveau = $_POST['niveau'];
    $prix = $_POST['prix'];
    $duree = trim($_POST['duree']);
    $instructor = trim($_POST['instructor']);

    if(!preg_match("/^[A-Za-zÀ-ÿ\s]+$/", $titre)){
        $errors['titre'] = "Titre invalide";
    }

    if(strlen($description) < 5){
        $errors['description'] = "Description trop courte";
    }

    if(empty($domaine)){
        $errors['domaine'] = "Domaine obligatoire";
    }

    if(!is_numeric($prix) || $prix <= 0){
        $errors['prix'] = "Prix invalide";
    }

    if(empty($duree)){
        $errors['duree'] = "Durée obligatoire";
    }

    if(!preg_match("/^[A-Za-zÀ-ÿ\s]+$/", $instructor)){
        $errors['instructor'] = "Nom invalide";
    }

    if(empty($errors)){
        $controller->addFormation($_POST);

        header("Location: index.php?page=list");
        exit;
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - DayNight Admin</title>
        <script>
            // Prevent flash of white in dark mode - runs before CSS/page render
            document.documentElement.classList.remove('carbon');
        </script>
        <link rel="stylesheet" href="templatemo-daynight-style.css">
        <!--a

    TemplateMo 608 DayNight Admin

    https://templatemo.com/tm-608-daynight-admin

    -->
    </head>
    <body>
        <!-- Mobile Menu Overlay -->
        <div class="mobile-menu-overlay"></div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <a href="index.php" class="logo">
                    <div class="logo-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    DayNight
                </a>
                <button class="mobile-menu-close" onclick="closeMobileMenu()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <nav class="mobile-menu-nav">
                <a href="index.php" class="active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
                <a href="projects.html">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                    </svg>
                    Projects
                </a>
                <a href="inbox.html">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    Inbox
                </a>
                <a href="analytics.html">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    Analytics
                </a>
                <a href="settings.html">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    Settings
                </a>
            </nav>
            <div class="mobile-menu-footer">
                <a href="login.html" class="mobile-logout-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Logout
                </a>
                <div class="theme-toggle">
                    <button class="theme-btn theme-btn-snow active" onclick="setTheme('snow')" title="Snow Edition">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                    </button>
                    <button class="theme-btn theme-btn-carbon" onclick="setTheme('carbon')" title="Carbon Edition">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-container">
            <!-- Top Navigation -->
            <nav class="top-nav">   
                <div class="nav-container">
                    <div class="nav-left">
                        <a href="index.html" class="logo">
                            <div class="logo-icon">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            DayNight
                        </a>
                        </div>

                <div class="nav-menu">

                    <a href="index.php?page=users" class="nav-link">Gestion Users</a>

                    <a href="index.php?page=reclamations" class="nav-link">Gestion Reclamations</a>

                    <a href="index.php?page=portfolio" class="nav-link">Gestion Portfolio</a>

                    <a href="index.php?page=formations" class="nav-link">Gestion Formations</a>

                </div>
                    </div>
                    <div class="nav-right">
                        <div class="theme-toggle">
                            <button class="theme-btn theme-btn-snow active" onclick="setTheme('snow')" title="Snow Edition">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="5"/>
                                    <line x1="12" y1="1" x2="12" y2="3"/>
                                    <line x1="12" y1="21" x2="12" y2="23"/>
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                    <line x1="1" y1="12" x2="3" y2="12"/>
                                    <line x1="21" y1="12" x2="23" y2="12"/>
                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                                </svg>
                            </button>
                            <button class="theme-btn theme-btn-carbon" onclick="setTheme('carbon')" title="Carbon Edition">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                                </svg>
                            </button>
                        </div>
                        <button class="user-menu">
                            <div class="user-avatar">A</div>
                            <span class="user-name">Alex</span>
                        </button>
                        <a href="login.html" class="btn-logout" title="Logout">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                        </a>
                        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="3" y1="12" x2="21" y2="12"/>
                                <line x1="3" y1="6" x2="21" y2="6"/>
                                <line x1="3" y1="18" x2="21" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
    <main class="main-content" style="margin-left:260px; padding:20px;">

    <?php if($page == 'dashboard' || $page == 'formations'): ?>

        <h2>Gestion Formations</h2>
        <h2>Dashboard</h2>

    <!-- BUTTONS -->
    <a href="index.php?page=add" class="btn btn-primary">➕ Ajouter Formation</a>
    <a href="index.php?page=list" class="btn btn-secondary">📋 Liste Formations</a>
    <a href="index.php?page=certificates" class="btn btn-secondary">🎓 Liste Certificates</a>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total</div>
                <div class="stat-value"><?= $total ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Débutant</div>
                <div class="stat-value"><?= $debutant ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Intermédiaire</div>
                <div class="stat-value"><?= $intermediaire ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Avancé</div>
                <div class="stat-value"><?= $avance ?></div>
            </div>
        </div>
        <div style="display:flex; gap:30px; margin-top:30px; flex-wrap:wrap;">

    <!-- PIE CHART -->
    <div style="background:white; padding:20px; border-radius:10px; width:400px;">
        <h3>Niveaux</h3>
        <canvas id="niveauChart"></canvas>
    </div>

    <!-- BAR CHART -->
    <div style="background:white; padding:20px; border-radius:10px; width:400px;">
        <h3>Revenu</h3>
        <canvas id="revenuChart"></canvas>
    </div>

</div>

    <?php elseif($page == 'list'): ?>

        <h2>Liste des formations</h2>
        <a href="index.php?page=formations" class="btn-retour">⬅ Retour</a>
        <table class="modern-table">
            <tr>
                <th>Titre</th>
                <th>Domaine</th>
                <th>Prix</th>
                <th>Niveau</th>
                <th>Actions</th>
            </tr>

            <?php foreach($formations as $f): ?>
            <tr>
                <td><?= $f['titre'] ?></td>
                <td><?= $f['domaine'] ?></td>
                <td><?= $f['prix'] ?></td>
                <td><?= $f['niveau'] ?></td>
                <td>
                    <a href="index.php?page=edit&id=<?= $f['id'] ?>">✏ Edit</a>
                    <a href="index.php?page=delete&id=<?= $f['id'] ?>" onclick="return confirm('Delete?')">🗑 Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

    <?php elseif($page == 'add'): ?>

<div class="form-card">

        <h2>Ajouter Formation</h2>

<form method="POST">

    <!-- TITRE -->
    <div>
        <input type="text" name="titre" placeholder="Titre"
        value="<?= $_POST['titre'] ?? '' ?>">

        <?php if(isset($errors['titre'])): ?>
            <small style="color:#EC4899; display:block;">
                <?= $errors['titre'] ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- DESCRIPTION -->
    <div>
        <textarea name="description"><?= $_POST['description'] ?? '' ?></textarea>

        <?php if(isset($errors['description'])): ?>
            <small style="color:#EC4899; display:block;">
                <?= $errors['description'] ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- DOMAINE -->
    <div>
        <input type="text" name="domaine" placeholder="Domaine"
        value="<?= $_POST['domaine'] ?? '' ?>">

        <?php if(isset($errors['domaine'])): ?>
            <small style="color:#EC4899; display:block;">
                <?= $errors['domaine'] ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- NIVEAU -->
    <div>
        <select name="niveau">
            <option value="">-- Choisir --</option>
            <option value="debutant">Débutant</option>
            <option value="intermediaire">Intermédiaire</option>
            <option value="avance">Avancé</option>
        </select>

        <?php if(isset($errors['niveau'])): ?>
            <small style="color:#EC4899; display:block;">
                <?= $errors['niveau'] ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- PRIX -->
    <div>
        <input type="number" name="prix" placeholder="Prix"
        value="<?= $_POST['prix'] ?? '' ?>">

        <?php if(isset($errors['prix'])): ?>
            <small style="color:#EC4899; display:block;">
                <?= $errors['prix'] ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- DUREE -->
    <div>
        <input type="text" name="duree" placeholder="Durée"
        value="<?= $_POST['duree'] ?? '' ?>">

        <?php if(isset($errors['duree'])): ?>
            <small style="color:#EC4899; display:block;">
                <?= $errors['duree'] ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- INSTRUCTOR -->
    <div>
        <input type="text" name="instructor" placeholder="Instructor"
        value="<?= $_POST['instructor'] ?? '' ?>">

        <?php if(isset($errors['instructor'])): ?>
            <small style="color:#EC4899; display:block;">
                <?= $errors['instructor'] ?>
            </small>
        <?php endif; ?>
    </div>

    <br>
    <button type="submit">Ajouter</button>
    <a href="index.php?page=formations" class="btn-retour">⬅ Retour</a>

</form>
</div>
<?php elseif($page == 'certificates'): ?>

<h2>Liste des certificats</h2>

<a href="index.php?page=formations" class="btn-retour">⬅ Retour</a>


<table class="modern-table">
    <tr>
        <th>Nom</th>
        <th>Formation</th>
        <th>Date</th>
        <th>Code</th>
        <th>Actions</th>
    </tr>

    <?php foreach($certificates ?? [] as $c): ?>
    <tr>
        <td><?= $c['user_name'] ?></td>
        <td><?= $c['titre'] ?></td>
        <td><?= $c['date_obtention'] ?></td>
        <td><?= $c['certificate_code'] ?></td>
        <td>
            <a href="index.php?page=editCert&id=<?= $c['id'] ?>">✏ Edit</a>
            <a href="index.php?page=deleteCert&id=<?= $c['id'] ?>" onclick="return confirm('Delete?')">🗑 Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>


    <?php elseif($page == 'edit'): ?>

    <?php
    $id = $_GET['id'] ?? 0;
    foreach($formations as $f){
        if($f['id'] == $id){
            $formation = $f;
            break;
        }
    }
    ?>

    <h2>Modifier Formation</h2>

<div class="form-card">

    <h2>Modifier Formation</h2>

    <form method="POST" action="updateFormationAction.php">

        <input type="hidden" name="id" value="<?= $formation['id'] ?>">

        <!-- TITRE -->
        <input type="text" name="titre" placeholder="Titre"
        value="<?= $formation['titre'] ?>">

        <!-- DESCRIPTION -->
        <textarea name="description"><?= $formation['description'] ?? '' ?></textarea>

        <!-- DOMAINE -->
        <input type="text" name="domaine" placeholder="Domaine"
        value="<?= $formation['domaine'] ?>">

        <!-- NIVEAU -->
        <select name="niveau">
            <option value="debutant" <?= $formation['niveau']=="debutant"?"selected":"" ?>>Débutant</option>
            <option value="intermediaire" <?= $formation['niveau']=="intermediaire"?"selected":"" ?>>Intermédiaire</option>
            <option value="avance" <?= $formation['niveau']=="avance"?"selected":"" ?>>Avancé</option>
        </select>
        <input type="number" name="prix" value="<?= $formation['prix'] ?>">

        <input type="text" name="duree" value="<?= $formation['duree'] ?>">

        <input type="text" name="instructor" value="<?= $formation['instructor'] ?>">

        <button type="submit" class="btn-primary">Modifier</button>

    </form>
    

</div>
<?php endif; ?>

    </main>

            <!-- Footer -->
            <footer class="footer">
                <p>&copy; 2026 DayNight Admin. Designed by <a href="https://www.templatemo.com" target="_blank" rel="nofollow">TemplateMo</a></p>
            </footer>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="templatemo-daynight-script.js"></script>

    <script>
    localStorage.setItem('daynight-theme', 'snow');
    document.documentElement.classList.remove('carbon');
    </script>
<script>
const debutant = <?= $debutant ?>;
const intermediaire = <?= $intermediaire ?>;
const avance = <?= $avance ?>;
const revenu = <?= $revenu ?>;

// 🎯 PIE CHART
new Chart(document.getElementById('niveauChart'), {
    type: 'pie',
    data: {
        labels: ['Débutant', 'Intermédiaire', 'Avancé'],
        datasets: [{
            data: [debutant, intermediaire, avance],
            backgroundColor: [
                '#EC4899',
                '#8B5CF6',
                '#3B82F6'
            ]
        }]
    },
    options: {
        animation: {
            duration: 1500
        }
    }
});

// 💰 BAR CHART
new Chart(document.getElementById('revenuChart'), {
    type: 'bar',
    data: {
        labels: ['Revenu Total'],
        datasets: [{
            label: 'TND',
            data: [revenu],
            backgroundColor: '#8B5CF6'
        }]
    },
    options: {
        animation: {
            duration: 1500
        }
    }
});
</script>
    </body>
    </html>
