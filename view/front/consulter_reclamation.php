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
    $_SESSION['error_message'] = "❌ Vous n'êtes pas autorisé à consulter cette réclamation";
    header("Location: mes_reclamations.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang=\"fr\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Consulter Réclamation</title>
    <script>
        // Prevent flash of white in dark mode
        if (localStorage.getItem('daynight-theme') === 'carbon') {
            document.documentElement.classList.add('carbon');
        }
    </script>

    <!-- TEMPLATE CSS -->
    <link rel=\"stylesheet\" href=\"/service_client/assets/css/templatemo-daynight-style.css\">
    
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

/* Details Box - Cohérent avec le template */
.details-box {
    background: var(--bg-primary);
    padding: 40px;
    border-radius: 16px;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    margin: 30px;
}

.details-box h2 {
    color: var(--text-primary);
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.reclamation-number {
    background: linear-gradient(135deg, var(--accent), #38bdf8);
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    margin-top: 15px;
    margin-bottom: 30px;
}

.reclamation-number-label {
    color: var(--text-secondary);
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 30px;
}

.detail-row {
    padding: 20px;
    background: var(--bg-surface);
    border-radius: 10px;
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.detail-row:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 12px rgba(56, 189, 248, 0.1);
}

.detail-label {
    font-weight: 600;
    color: var(--accent);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.detail-value {
    color: var(--text-primary);
    font-size: 15px;
    line-height: 1.6;
    word-wrap: break-word;
}

.detail-value a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
    border-bottom: 1px dotted var(--accent);
    transition: var(--transition);
}

.detail-value a:hover {
    border-bottom-style: solid;
}

.detail-row.full-width {
    grid-column: 1 / -1;
}

.detail-value.message-box {
    background: var(--bg-primary);
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid var(--accent);
    min-height: 120px;
    white-space: pre-wrap;
    word-wrap: break-word;
    font-size: 14px;
    line-height: 1.8;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.badge-type {
    background: linear-gradient(135deg, var(--accent-light), #cffafe);
    color: var(--accent);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    border: 1px solid var(--accent-light);
}

.action-buttons {
    margin-top: 40px;
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
    padding-top: 30px;
    border-top: 1px solid var(--border-color);
}

.action-buttons a, .action-buttons button {
    padding: 14px 28px;
    border-radius: 10px;
    text-decoration: none;
    cursor: pointer;
    border: none;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 14px;
    min-width: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-modifier {
    background: linear-gradient(135deg, var(--accent), #38bdf8);
    color: white;
    box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
}

.btn-modifier:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(56, 189, 248, 0.4);
}

.btn-retour {
    background: var(--bg-surface);
    color: var(--text-primary);
    border: 2px solid var(--border-color);
}

.btn-retour:hover {
    background: var(--border-color);
    border-color: var(--accent);
    color: var(--accent);
    transform: translateY(-3px);
}

.btn-supprimer {
    background: linear-gradient(135deg, #ef4444, #f87171);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-supprimer:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
}

    @media (max-width: 768px) {
    .sidebar {
        width: 200px;
    }
    
    body {
        padding-left: 200px !important;
    }
    
    .details-box {
        margin: 20px;
        padding: 25px;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons a, .action-buttons button {
        width: 100%;
        min-width: unset;
    }
    
    .details-box h2 {
        font-size: 1.5rem;
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
    </div>    <div class="theme-toggle">
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
    </div></nav>

<!-- DÉTAILS -->
<div class="details-box" style="margin-top: 100px;">
    <!-- HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid var(--border-color);">
        <div>
            <h2 style="margin: 0 0 8px 0;">Détails de la Réclamation</h2>
            <p style="margin: 0; color: var(--text-secondary); font-size: 13px;">Consultez tous les détails de votre demande</p>
        </div>
        <div style="background: linear-gradient(135deg, var(--accent), #0ea5e9); color: white; padding: 16px 24px; border-radius: 12px; text-align: right; box-shadow: 0 8px 16px rgba(56, 189, 248, 0.3);">
            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9;">N° Réclamation</div>
            <div style="font-size: 28px; font-weight: 700; margin-top: 4px;">N°<?= str_pad($reclamation['id_reclamation'], 6, '0', STR_PAD_LEFT) ?></div>
        </div>
    </div>

    <!-- SECTION 1: INFORMATIONS PERSONNELLES -->
    <div style="margin-bottom: 35px;">
        <h3 style="font-size: 14px; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 20px 0;">👤 Informations Personnelles</h3>
        <div class="detail-grid" style="grid-template-columns: 1fr 1fr;">
            <div class="detail-row">
                <div style="width: 100%;">
                    <div class="detail-label">Nom</div>
                    <div class="detail-value" style="font-weight: 500; font-size: 16px;"><?= htmlspecialchars($reclamation['nom']) ?></div>
                </div>
            </div>
            <div class="detail-row">
                <div style="width: 100%;">
                    <div class="detail-label">Prénom</div>
                    <div class="detail-value" style="font-weight: 500; font-size: 16px;"><?= htmlspecialchars($reclamation['prenom']) ?></div>
                </div>
            </div>
            <div class="detail-row" style="grid-column: 1 / -1;">
                <div style="width: 100%;">
                    <div class="detail-label">📧 Email</div>
                    <div class="detail-value"><a href="mailto:<?= htmlspecialchars($reclamation['email']) ?>" style="color: var(--accent); font-weight: 500; text-decoration: none; border-bottom: 2px solid transparent; transition: var(--transition);"><?= htmlspecialchars($reclamation['email']) ?></a></div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: DÉTAILS DE LA RÉCLAMATION -->
    <div style="margin-bottom: 35px;">
        <h3 style="font-size: 14px; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 20px 0;">📋 Détails de la Réclamation</h3>
        <div class="detail-grid" style="grid-template-columns: 1fr 1fr;">
            <div class="detail-row">
                <div style="width: 100%;">
                    <div class="detail-label">Sujet</div>
                    <div class="detail-value" style="font-weight: 600; font-size: 16px; color: var(--text-primary);"><?= htmlspecialchars($reclamation['sujet']) ?></div>
                </div>
            </div>
            <div class="detail-row">
                <div style="width: 100%;">
                    <div class="detail-label">Type de Problème</div>
                    <div class="detail-value">
                        <span class="badge-type" style="background: linear-gradient(135deg, var(--accent-light), #cffafe); color: var(--accent); padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-block; border: 1px solid var(--accent-light);">🏷️ <?= htmlspecialchars($reclamation['type_probleme']) ?></span>
                    </div>
                </div>
            </div>
            <div class="detail-row">
                <div style="width: 100%;">
                    <div class="detail-label">📅 Date de Création</div>
                    <div class="detail-value" style="font-weight: 500;"><?= date('d/m/Y', strtotime($reclamation['date_creation'])) ?> à <span style="color: var(--accent); font-weight: 600;"><?= date('H:i', strtotime($reclamation['date_creation'])) ?></span></div>
                </div>
            </div>
            <div class="detail-row">
                <div style="width: 100%;">
                    <div class="detail-label">⏱️ Statut</div>
                    <div class="detail-value">
                        <span style="background: linear-gradient(135deg, #dbeafe, #e0f2fe); color: #0369a1; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">✓ En Cours</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: MESSAGE -->
    <div style="margin-bottom: 35px;">
        <h3 style="font-size: 14px; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 20px 0;">📝 Message</h3>
        <div class="detail-value message-box" style="background: linear-gradient(135deg, var(--bg-surface), transparent); padding: 25px; border-left: 4px solid var(--accent); border-radius: 10px; min-height: 140px; font-size: 15px; line-height: 1.8; color: var(--text-primary);"><?= htmlspecialchars($reclamation['message'] ?? 'Aucun message fourni') ?></div>
    </div>

    <!-- ACTIONS -->
    <div class="action-buttons" style="padding-top: 35px; border-top: 2px solid var(--border-color);">
        <a href="modifier_reclamation.php?id=<?= $reclamation['id_reclamation'] ?>" class="btn-modifier" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <span>✏️</span> Modifier
        </a>
        <a href="mes_reclamations.php" class="btn-retour" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <span>←</span> Retour
        </a>
        <a href="/service_client/controller/reclamationcontroller.php?action=delete&id=<?= $reclamation['id_reclamation'] ?>" 
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ? Cette action est définitive.');" 
           class="btn-supprimer" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <span>🗑️</span> Supprimer
        </a>
    </div>
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
