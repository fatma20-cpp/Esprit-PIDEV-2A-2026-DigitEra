<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


<?php
require_once '../Controller/DemandeController.php';
require_once '../Controller/OffreController.php';

$demandeC = new DemandeController();
$offreC = new OffreController();
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
die("No offer selected");
}

$id_offre = $_GET['id'];  

$offer = $offreC->recupererOffer($_GET['id']);

if (!$offer) {
die("Offer not found");
}

$demandes = $demandeC->getDemandesByOffer($_GET['id']);

// Flash message after action
$flash = $_GET['flash'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $id_demande = intval($_POST['id_demande']);

    if ($action === 'accept') {

        $demandeC->accepterDemande($id_demande, $id_offre);

        header("Location: ManageDemands.php?id=" . $id_offre . "&flash=accepted");
        exit();

    } elseif ($action === 'reject') {

        $demandeC->refuserDemande($id_demande);

        header("Location: ManageDemands.php?id=" . $id_offre . "&flash=rejected");
        exit();
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - DigitEra Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script>
    // Prevent flash of white in dark mode - runs before CSS/page render
    if (localStorage.getItem("daynight-theme") === "carbon") {
        document.documentElement.classList.add("carbon");
    }
    </script>
    <link rel="stylesheet" href="../css/templatemo-daynight-style.css" />
    <style>
    /* ── OFFER SUMMARY ── */
    .card {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px 30px;
        margin-bottom: 35px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;

    }

    .card h2 {
        margin: 0 0 6px;
        font-size: 22px;
        color: #0f172a;
    }

    .card .meta {
        display: flex;
        gap: 20px;
        font-size: 14px;
        color: #94a3b8;
        flex-wrap: wrap;
    }

    .card .meta span i {
        color: #38bdf8;
        margin-right: 5px;
    }

    .badge-closed {
        background: rgba(255, 100, 100, 0.2);
        color: #ff6b6b;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
    }

    .badge-open {
        background: rgba(0, 255, 136, 0.15);
        color: #00ff88;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
    }

    /* ── SECTION TITLE ── */
    .section-title {
        font-size: 22px;
        color: #0f172a;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title span {
        background: rgba(0, 255, 204, 0.12);
        color: #0f172a;
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 14px;
    }

    /* ── DEMANDE CARD ── */
    .demande-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 22px 25px;
        margin-bottom: 18px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 16px;
        align-items: start;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border-left: 4px solid transparent;
        transition: border-color 0.2s;
    }

    .demande-card.accepted {
        border-left-color: #00ff88;
    }

    .demande-card.rejected {
        border-left-color: #ff4d4d;
    }

    .demande-card.pending {
        border-left-color: #ffc107;
    }

    .demande-freelancer {
        font-size: 16px;
        font-weight: bold;
        color: #0f172a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .demande-freelancer i {
        color: #0f172a;
    }

    .demande-message {
        color: #94a3b8;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 14px;
    }

    .demande-meta {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: #64748b;
        flex-wrap: wrap;
    }

    .demande-meta i {
        color: #38bdf8;
        margin-right: 4px;
    }

    /* ── STATUS BADGE ── */
    .status-badge {
        display: inline-block;
        padding: 5px 13px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        margin-top: 12px;
    }

    .status-badge.pending {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    .status-badge.accepted {
        background: rgba(0, 255, 136, 0.15);
        color: #00ff88;
    }

    .status-badge.rejected {
        background: rgba(255, 77, 77, 0.15);
        color: #ff4d4d;
    }

    /* ── ACTION BUTTONS ── */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-width: 130px;
    }

    .btn-accept {
        background: linear-gradient(135deg, #00c6a2, #00e676);

    }

    .btn-accept:hover {
        opacity: 0.85;
    }

    .btn-reject {
        background: rgba(255, 77, 77, 0.12);
        color: #ff4d4d;
        border: 1px solid rgba(255, 77, 77, 0.3);
        padding: 10px 18px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 13px;
        transition: background 0.2s;
        width: 100%;
    }



    .btn-reject:hover {
        background: rgba(255, 77, 77, 0.22);
    }

    .btn-disabled {
        background: rgba(255, 255, 255, 0.05);
        color: #475569;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        cursor: not-allowed;
        width: 100%;
        text-align: center;
    }

    /* ── FLASH ── */
    .flash {
        padding: 14px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        font-size: 14px;
        font-weight: 500;
    }

    .flash.success {
        background: rgba(0, 255, 136, 0.12);
        color: #00ff88;
        border: 1px solid rgba(0, 255, 136, 0.25);
    }

    .flash.info {
        background: rgba(56, 189, 248, 0.12);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.25);
    }

    /* ── EMPTY ── */
    .empty {
        text-align: center;
        padding: 60px 20px;
        color: #475569;
    }

    .empty i {
        font-size: 36px;
        margin-bottom: 14px;
    }

    .empty p {
        font-size: 16px;
    }

    /* ── BACK BTN ── */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #38bdf8;
        text-decoration: none;
        font-size: 14px;
        margin-bottom: 22px;
        transition: color 0.2s;
    }

    .btn {
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
    }

    /* EDIT BUTTON */
    .btn-secondary {
        background: #f3f4f6;
        color: #111827;
        color: #0f172a;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
        transition: opacity 0.2s;
        width: 100%;
    }

    .btn-danger {
        background: #fee2e2;
        color: #b91c1c;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
        transition: opacity 0.2s;
        width: 100%;
    }

    .btn-danger:hover {
        background: #fecaca;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .back-btn:hover {
        color: #00ffcc;
    }
    </style>
    <!--
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
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                </div>
                DigitEra
            </a>
            <button class="mobile-menu-close" onclick="closeMobileMenu()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <nav class="mobile-menu-nav">
            <a href="UserDashboard.html" class="active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="3" y="14" width="7" height="7" rx="1" />
                    <rect x="14" y="14" width="7" height="7" rx="1" />
                </svg>
                Dashboard
            </a>
            <a href="projects.html">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                </svg>
                Projects
            </a>
            <a href="inbox.html">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                    <polyline points="22,6 12,13 2,6" />
                </svg>
                Inbox
            </a>
            <a href="analytics.html">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                </svg>
                Analytics
            </a>
            <a href="settings.html">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3" />
                    <path
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                </svg>
                Settings
            </a>
        </nav>
        <div class="mobile-menu-footer">
            <a href="login.html" class="mobile-logout-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                Logout
            </a>
            <div class="theme-toggle">
                <button class="theme-btn theme-btn-snow active" onclick="setTheme('snow')" title="Snow Edition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5" />
                        <line x1="12" y1="1" x2="12" y2="3" />
                        <line x1="12" y1="21" x2="12" y2="23" />
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                        <line x1="1" y1="12" x2="3" y2="12" />
                        <line x1="21" y1="12" x2="23" y2="12" />
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                    </svg>
                </button>
                <button class="theme-btn theme-btn-carbon" onclick="setTheme('carbon')" title="Carbon Edition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
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
                    <a href="index.php" class="logo">
                        <div class="logo-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                        </div>
                        DigitEra
                    </a>
                </div>
                <div class="nav-right">
                    <div class="theme-toggle">
                        <button class="theme-btn theme-btn-snow active" onclick="setTheme('snow')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="5" />
                                <line x1="12" y1="1" x2="12" y2="3" />
                                <line x1="12" y1="21" x2="12" y2="23" />
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                                <line x1="1" y1="12" x2="3" y2="12" />
                                <line x1="21" y1="12" x2="23" y2="12" />
                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                            </svg>
                        </button>
                        <button class="theme-btn theme-btn-carbon" onclick="setTheme('carbon')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                            </svg>
                        </button>
                    </div>
                    <button class="user-menu">
                        <div class="user-avatar">A</div>
                        <span class="user-name">Alex</span>
                    </button><a href="login.html" class="btn-logout" title="Logout"><svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg></a>
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <span>DigitEra</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="UserDashboard.html" class="nav-link active"><svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="7" height="7" rx="1" />
                        </svg>Dashboard</a>
                </div>
                <div class="nav-item">
                    <a href="projects.html" class="nav-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                        </svg>Projects</a>
                </div>
                <div class="nav-item">
                    <a href="inbox.html" class="nav-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>Inbox</a>
                </div>
                <div class="nav-item">
                    <a href="analytics.html" class="nav-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                        </svg>Analytics</a>
                </div>
                <div class="nav-item">
                    <a href="ListOffer.php" class="nav-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>Offers</a>
                </div>
                <div class="nav-item">
                    <a href="ListOfferAdmin.php" class="nav-link"><svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>Offers Admin</a>
                </div>
                <div class="nav-item">
                    <a href="FreelancerDemands.php" class="nav-link"><svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>Freelancer Demands</a>
                </div>
                <div class="nav-item">
                    <a href="AdminDemands.php" class="nav-link"><svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>Admin Demands</a>
                </div>
                <div class="nav-item">
                    <a href="settings.html" class="nav-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>Settings</a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <div style="margin-bottom:15px;">
                    <a href="ListOffer.php" class="btn btn-ghost">
                        ← Back to Offers
                    </a>
                </div>
                <h1 class="greeting">View Demands</h1>
                <p class="greeting-sub">
                    Review and manage proposals submitted by freelancers for this offer.
                </p>


            </div>
            <section class="dashboard-section">
                <div class="dashboard-container">


                    <!-- FLASH -->
                    <?php if ($flash === 'accepted'): ?>
                    <div class="flash success">
                        <i class="fa fa-check-circle"></i>
                        Proposal accepted. All other proposals have been rejected and the offer is now closed.
                    </div>
                    <?php elseif ($flash === 'rejected'): ?>
                    <div class="flash info">
                        <i class="fa fa-times-circle"></i>
                        Proposal rejected.
                    </div>
                    <?php endif; ?>

                    <!-- OFFER SUMMARY -->
                    <div class="card">
                        <div>
                            <h2><?php echo htmlspecialchars($offer['title']); ?></h2>
                            <div class="meta">
                                <span><i class="fa fa-money-bill"></i> <?php echo $offer['budget']; ?> DT</span>
                                <span><i class="fa fa-tag"></i> <?php echo $offer['category']; ?></span>
                                <span><i class="fa fa-calendar"></i> <?php echo $offer['deadline']; ?></span>
                            </div>
                        </div>
                        <?php if (isset($offer['status']) && $offer['status'] === 'closed'): ?>
                        <div class="badge-closed"><i class="fa fa-lock"></i> Closed</div>
                        <?php else: ?>
                        <div class="badge-open"><i class="fa fa-circle"></i> Open</div>
                        <?php endif; ?>
                    </div>

                    <!-- PROPOSALS -->
                    <div class="section-title">
                        Proposals
                        <span><?php echo count($demandes); ?></span>
                    </div>

                    <?php if (empty($demandes)): ?>
                    <div class="empty">
                        <i class="fa fa-inbox"></i>
                        <p>No proposals received yet.</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($demandes as $d): ?>
                    <div class="demande-card <?php echo htmlspecialchars($d['status']); ?>">

                        <!-- LEFT: INFO -->
                        <div>
                            <div class="demande-freelancer">
                                <i class="fa fa-user-circle"></i>
                                <?php echo $d['nom']; ?> <?php echo $d['prenom']; ?>

                            </div>

                            <div class="demande-message">
                                <?php echo htmlspecialchars($d['message']); ?>
                            </div>

                            <div class="demande-meta">
                                <span><i class="fa fa-coins"></i> <strong><?php echo $d['price']; ?> DT</strong></span>
                                <span><i class="fa fa-clock"></i> <?php echo $d['delivery_time']; ?> days</span>
                                <span><i class="fa fa-calendar"></i> <?php echo $d['created_at']; ?></span>
                            </div>

                            <div class="status-badge <?php echo htmlspecialchars($d['status']); ?>">
                                <?php echo ucfirst($d['status']); ?>
                            </div>
                        </div>

                        <!-- RIGHT: ACTIONS -->
                        <div class="action-buttons">
                            <?php if ($d['status'] === 'pending' && (!isset($offer['status']) || $offer['status'] !== 'closed')): ?>
                            <!-- ACCEPT -->
                            <form method="POST">
                                <input type="hidden" name="action" value="accept">
                                <input type="hidden" name="id_demande" value="<?php echo $d['id_demande']; ?>">
                                <input type="hidden" name="id_offer" value="<?php echo $id_offer; ?>">
                                <button type="submit" class="btn btn-secondary"
                                    onclick="return confirm('Accept this proposal? All others will be rejected.')">
                                    Accept
                                </button>
                            </form>
                            <!-- REJECT -->
                            <form method="POST">
                                <input type="hidden" name="action" value="reject">
                                <input type="hidden" name="id_demande" value="<?php echo $d['id_demande']; ?>">
                                <input type="hidden" name="id_offer" value="<?php echo $id_offer; ?>">
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Reject this proposal?')">
                                    Reject </button>
                            </form>
                            <?php else: ?>
                            <div class="btn-disabled">
                                <i class="fa fa-lock"></i> Locked
                            </div>
                            <?php endif; ?>
                        </div>

                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </section>

        </main>

        <!-- Footer -->
        <footer class="footer">
            <p>
                &copy; 2026 DigitEra Admin. Designed by
                <a href="https://www.templatemo.com" target="_blank" rel="nofollow">TemplateMo</a>
            </p>
        </footer>
    </div>
    <script src="../js/templatemo-daynight-script.js"></script>
</body>

</html>