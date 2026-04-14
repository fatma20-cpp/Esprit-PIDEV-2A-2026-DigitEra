<?php
require_once "../../config/database.php";
require_once "../../model/Reclamation.php";
session_start();

// 🔹 Créer la connexion à la base de données
$database = new Database();
$conn = $database->connect();

$reclamationModel = new Reclamation($conn);

// 🔹 Filtrer par client_id
if (isset($_SESSION['client_id'])) {
    $reclamations = $reclamationModel->readByClientId($_SESSION['client_id']);
} else {
    $reclamations = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réclamations</title>
    <script>
        // Prevent flash of white in dark mode
        if (localStorage.getItem('daynight-theme') === 'carbon') {
            document.documentElement.classList.add('carbon');
        }
    </script>

    <!-- TEMPLATE CSS -->
    <link rel="stylesheet" href="/service_client/assets/css/templatemo-daynight-style.css">
    
    <style>
/* Sidebar - Cohérent avec le template */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100vh;
    background: var(--bg-primary);
    border-right: 1px solid var(--border-color);
    padding-top: 80px;
    z-index: 999;
    overflow-y: auto;
}

.sidebar h3 {
    color: var(--text-primary);
    text-align: center;
    padding: 20px 15px 10px 15px;
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    border-bottom: 1px solid var(--border-color);
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin: 0;
}

.sidebar ul li a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-secondary);
    text-decoration: none;
    padding: 12px 15px;
    transition: var(--transition);
    border-left: 3px solid transparent;
    font-size: 14px;
    font-weight: 500;
}

.sidebar ul li a:hover {
    background: var(--bg-surface);
    color: var(--text-primary);
    border-left-color: var(--accent);
}

.sidebar ul li a.active {
    background: var(--accent-light);
    color: var(--accent);
    border-left-color: var(--accent);
}

/* Ajuster le corps basé sur la sidebar */
body {
    padding-left: 250px !important;
}

/* Tables - Cohérent avec le template */
table {
    width: 100%;
    border-collapse: collapse;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
}

table th {
    background: var(--bg-surface);
    color: var(--text-primary);
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    font-size: 0.875rem;
    border-bottom: 1px solid var(--border-color);
}

table td {
    padding: 12px 15px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
    font-size: 0.875rem;
}

table tr:last-child td {
    border-bottom: none;
}

table tr:hover {
    background: var(--bg-surface);
}

table a {
    color: var(--accent);
    text-decoration: none;
    margin: 0 5px;
    font-weight: 500;
    transition: var(--transition);
}

table a:hover {
    color: var(--accent-hover);
}

/* Container - Cohérent avec le template */
.container {
    background: var(--bg-primary);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow);
    margin-left: 30px;
    margin-right: 30px;
}

.container h2 {
    color: var(--text-primary);
    border-bottom: 2px solid var(--accent);
    padding-bottom: 15px;
    margin-bottom: 25px;
    font-size: 1.5rem;
    font-weight: 700;
}

/* Messages */
.success-message {
    background: rgba(34, 197, 94, 0.05);
    border-left: 3px solid var(--success);
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
}

.success-message p {
    color: var(--success);
    margin: 0;
    font-weight: 600;
}

.empty-state {
    background: rgba(245, 158, 11, 0.05);
    border-left: 3px solid var(--warning);
    padding: 15px;
    border-radius: 8px;
}

.empty-state p {
    color: var(--warning);
    margin: 5px 0;
}

.empty-state a {
    color: var(--warning);
    text-decoration: none;
    font-weight: 600;
}

@media (max-width: 768px) {
    .sidebar {
        width: 200px;
    }
    
    body {
        padding-left: 200px !important;
    }
    
    .container {
        margin: 20px;
    }
}

/* Theme Toggle */
.theme-toggle {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-left: auto;
}

.theme-btn {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    width: 36px;
    height: 36px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.theme-btn:hover {
    background: var(--bg-primary);
    border-color: var(--accent);
}

.theme-btn.active {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
}

.theme-btn svg {
    width: 18px;
    height: 18px;
}

#navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 18px;
}

.logo-img {
    height: 40px;
    width: auto;
    object-fit: contain;
}

.logo-text {
    color: var(--text-primary);
}
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>📋 Menu Client</h3>
    <ul>
        <li><a href="ajouterreclamationa.php"><span>➕</span> Ajouter Réclamation</a></li>
        <li><a href="mes_reclamations.php" class="active"><span>📋</span> Mes Réclamations</a></li>
        <li><a href="#"><span>⚙️</span> Paramètres</a></li>
        <li><a href="#"><span>❓</span> Aide</a></li>
    </ul>
</div>

<!-- NAVBAR -->
<nav id="navbar">
    <div class="nav-container">
        <a href="mes_reclamations.php" class="logo">
            <img src="../../assets/images/logo.jpeg" alt="Service Client Logo" class="logo-img">
            <span class="logo-text">Service Client</span>
        </a>
    </div>
    <div class="theme-toggle">
        <button class="theme-btn theme-btn-snow active" onclick="setTheme('snow')" title="Mode Clair">
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
        <button class="theme-btn theme-btn-carbon" onclick="setTheme('carbon')" title="Mode Sombre">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
        </button>
    </div>
</nav>

<div style="margin-top:100px;"></div>

<!-- TABLE -->
<div class="container">
    <h2>📋 Mes Réclamations</h2>

    <?php
    if (isset($_SESSION['client_id'])) {
        echo "<div style='background: rgba(34, 197, 94, 0.05); border-left: 3px solid var(--success); padding: 15px; margin-bottom: 20px; border-radius: 8px;'>";
        echo "<p style='color: var(--success); margin: 0; font-weight: 600;'>📋 Votre ID Client: <strong>" . $_SESSION['client_id'] . "</strong></p>";
        echo "</div>";
    }
    
    if (isset($_SESSION['success_message'])) {
        echo "<div class='success-message'>";
        echo "<p>" . $_SESSION['success_message'] . "</p>";
        echo "</div>";
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        echo "<div style='background: rgba(239, 68, 68, 0.05); border-left: 3px solid var(--danger); padding: 15px; margin-bottom: 20px; border-radius: 8px;'>";
        echo "<p style='color: var(--danger); margin: 0; font-weight: 600;'>" . $_SESSION['error_message'] . "</p>";
        echo "</div>";
        unset($_SESSION['error_message']);
    }
    ?>

    <?php if (count($reclamations) > 0) { ?>
    <table border="1" style="margin-top: 20px;">
        <tr>
            <th>🔢 N° Réclamation</th>
            <th>💬 Sujet</th>
            <th>🏷️ Type</th>
            <th>📝 Message</th>
            <th style="text-align: center;">⚙️ Actions</th>
        </tr>

        <?php foreach ($reclamations as $row) { ?>
            <tr>
                <td><strong><?= $row['id_reclamation'] ?></strong></td>
                <td><?= substr($row['sujet'], 0, 30) ?><?= strlen($row['sujet']) > 30 ? '...' : '' ?></td>
                <td><span style="background: var(--accent-light); color: var(--accent); padding: 5px 8px; border-radius: 6px; font-size: 12px; font-weight: 500;"><?= $row['type_probleme'] ?></span></td>
                <td><?= substr($row['message'], 0, 40) ?><?= strlen($row['message']) > 40 ? '...' : '' ?></td>
                <td style="text-align: center;">
                    <a href="consulter_reclamation.php?id=<?= $row['id_reclamation'] ?>" title="Consulter">👁️ Voir</a>
                    <a href="modifier_reclamation.php?id=<?= $row['id_reclamation'] ?>" title="Modifier">✏️ Modifier</a>
                    <a href="/service_client/controller/reclamationcontroller.php?action=delete&id=<?= $row['id_reclamation'] ?>" 
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?');"
                       title="Supprimer">🗑️ Supprimer</a>
                </td>
            </tr>
        <?php } ?>

    </table>
    <?php } else { ?>
        <div class="empty-state" style="margin-top: 20px;">
            <p>📭 <?php echo isset($_SESSION['client_id']) ? "Aucune réclamation enregistrée pour le moment." : "Veuillez d'abord soumettre une réclamation pour voir vos réclamations."; ?></p>
            <a href="ajouterreclamationa.php">➕ Ajouter une réclamation →</a>
        </div>
    <?php } ?>
</div>

<!-- JS TEMPLATE -->
<script src="/service_client/assets/js/templatemo-daynight-script.js"></script>

<script>
// Theme toggle
function setTheme(theme) {
    localStorage.setItem('daynight-theme', theme);
    if (theme === 'carbon') {
        document.documentElement.classList.add('carbon');
        document.body.classList.add('carbon');
    } else {
        document.documentElement.classList.remove('carbon');
        document.body.classList.remove('carbon');
    }
    updateThemeButtons();
}

function updateThemeButtons() {
    const theme = localStorage.getItem('daynight-theme') || 'snow';
    document.querySelectorAll('.theme-btn-snow').forEach(btn => {
        btn.classList.toggle('active', theme === 'snow');
    });
    document.querySelectorAll('.theme-btn-carbon').forEach(btn => {
        btn.classList.toggle('active', theme === 'carbon');
    });
}

// Initialize theme buttons on page load
window.addEventListener('load', updateThemeButtons);
</script>

</body>
</html>