<?php
require_once "../../config/database.php";
require_once "../../model/Reclamation.php";
session_start();

// 🔹 Créer la connexion à la base de données
$database = new Database();
$conn = $database->connect();

$model = new Reclamation($conn);

// 🔹 Vérifier que le client est identifié
$clientId = $_SESSION['client_id'] ?? null;
if (!$clientId) {
    $_SESSION['error_message'] = "❌ Veuillez d'abord soumettre une réclamation pour accéder à cette page";
    header("Location: ajouterreclamationa.php");
    exit;
}

// 🔹 Vérifier que la réclamation appartient au client
$id = $_GET['id'] ?? null;
$reclamation = $model->getByIdAndClientId($id, $clientId);

if (!$reclamation) {
    $_SESSION['error_message'] = "❌ Vous n'êtes pas autorisé à modifier cette réclamation";
    header("Location: mes_reclamations.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Réclamation</title>
    <script>
        // Prevent flash of white in dark mode
        if (localStorage.getItem('daynight-theme') === 'carbon') {
            document.documentElement.classList.add('carbon');
        }
    </script>

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

/* Formulaires - Cohérent avec le template */
input, select, textarea {
    background: var(--bg-primary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    padding: 10px 12px;
    margin-top: 5px;
    border-radius: 8px;
    font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 0.9375rem;
    width: 100%;
    transition: var(--transition);
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-light);
}

button {
    background: var(--accent);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    width: 100%;
    transition: var(--transition);
    font-size: 0.9375rem;
}

button:hover {
    background: var(--accent-hover);
}

.container {
    background: var(--bg-primary);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow);
    margin: 30px;
    max-width: 600px;
}

.container h2 {
    color: var(--text-primary);
    border-bottom: 2px solid var(--accent);
    padding-bottom: 15px;
    margin-bottom: 25px;
    font-size: 1.5rem;
    font-weight: 700;
}

label {
    color: var(--text-primary);
    font-weight: 500;
    font-size: 14px;
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

.btn-retour {
    display: block;
    text-align: center;
    margin-top: 10px;
    padding: 10px;
    background: var(--bg-surface);
    color: var(--text-primary);
    border-radius: 8px;
    text-decoration: none;
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.btn-retour:hover {
    background: var(--border-color);
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

<!-- FORMULAIRE DE MODIFICATION -->
<div class="container" style="margin-top: 100px;">

    <h2>✏️ Modifier votre réclamation</h2>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo "<div class='success-message'>";
        echo "<p>" . $_SESSION['success_message'] . "</p>";
        echo "</div>";
        unset($_SESSION['success_message']);
    }
    ?>

    <form action="/service_client/controller/reclamationcontroller.php" method="POST">

        <!-- ID caché -->
        <input type="hidden" name="id" value="<?= $reclamation['id_reclamation'] ?>">

        <label>🔢 N° Réclamation: <strong><?= $reclamation['id_reclamation'] ?></strong></label><br><br>

        <label for="sujet">💬 Sujet (max 50 caractères)</label><br>
        <input type="text" id="sujet" name="sujet" value="<?= $reclamation['sujet'] ?>" maxlength="50"><br><br>

        <label for="type">🏷️ Type de problème</label><br>
        <select id="type" name="type_probleme">
            <option value="Service" <?= $reclamation['type_probleme'] == 'Service' ? 'selected' : '' ?>>Service</option>
            <option value="Bug" <?= $reclamation['type_probleme'] == 'Bug' ? 'selected' : '' ?>>Bug</option>
        </select><br><br>

        <label for="message">📝 Message (max 200 caractères)</label><br>
        <textarea id="message" name="message" maxlength="200" rows="5"><?= $reclamation['message'] ?></textarea><br><br>

        <button type="submit" name="update">✅ Modifier la réclamation</button>
        <a href="mes_reclamations.php" class="btn-retour">← Retour à mes réclamations</a>

    </form>

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