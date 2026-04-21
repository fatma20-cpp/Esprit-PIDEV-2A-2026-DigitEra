<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();

require_once '../Controller/DemandeController.php';



$id_freelancer = 3; // HARDCODED FOR NOW, SHOULD COME FROM SESSION

$demandeC = new DemandeController();
$demandes = $demandeC->getAllDemandes();

// TAB FILTER
$tab = $_GET['tab'] ?? 'all';

if ($tab === 'accepted') {
    $demandes = array_filter($demandes, fn($d) => $d['status'] === 'accepted');
} elseif ($tab === 'rejected') {
    $demandes = array_filter($demandes, fn($d) => $d['status'] === 'rejected');
} elseif ($tab === 'pending') {
    $demandes = array_filter($demandes, fn($d) => $d['status'] === 'pending');
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Offers</title>
    <link rel="stylesheet" href="../css/templatemo-daynight-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    .meta-line {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .meta-line svg {
        width: 16px;
        height: 16px;
        stroke: #64748b;
        fill: none;
        stroke-width: 2;
    }

    .stat-icon svg {
        width: 20px;
        height: 20px;
        stroke: white;
        fill: none;
        stroke-width: 2;
    }

    /* CARD SOFT STYLE */
    .card {
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        padding: 18px;
        transition: all 0.25s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        border-color: #d1d5db;
    }

    /* TITLE */
    .stat-title {
        font-weight: 600;
        font-size: 15px;
        color: #111827;
    }

    /* CATEGORY (MAKE IT LOOK LIKE TAG) */
    .stat-category {
        display: inline-block;
        font-size: 11px;
        font-weight: 500;
        color: #2563eb;
        background: #eff6ff;
        padding: 4px 10px;
        border-radius: 999px;
        margin: 8px 0;
    }

    /* DESCRIPTION */
    .stat-description {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 10px;
    }

    /* META LINES */
    .meta-line {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #374151;
    }

    /* ICONS */
    .meta-line svg {
        width: 15px;
        height: 15px;
        stroke: #9ca3af;
        stroke-width: 2;
        fill: none;
    }

    /* MAKE PRICE STAND OUT */
    .meta-line strong {
        color: #111827;
        font-weight: 600;
    }

    /* ACTIONS */
    .card .btn {
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
    }

    /* EDIT BUTTON */
    .btn-secondary {
        background: #f3f4f6;
        color: #111827;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    /* DELETE BUTTON */
    .btn-danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .btn-danger:hover {
        background: #fecaca;
    }

    /* HEADER ICON SOFT */
    .stat-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon svg {
        width: 18px;
        height: 18px;
        stroke: #374151;
    }
    </style>
</head>

<body>

    <div class="app-container">

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
                    <a href="UserDashboard.html" class="nav-link"><svg viewBox="0 0 24 24" fill="none"
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
                    <a href="ListOfferAdmin.php" class="nav-link "><svg viewBox="0 0 24 24" fill="none"
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
                    <a href="AdminDemands.php" class="nav-link active"><svg viewBox="0 0 24 24" fill="none"
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



        <!-- MAIN -->
        <main class="main-content">

            <!-- HEADER -->
            <div class="page-header-offers">
                <div>
                    <h1 class="greeting">Offers Requests - Admin</h1>
                    <p class="greeting-sub">Manage your published offers requests

                    </p>
                </div>


            </div>

            <div class="settings-nav">

                <a href="?tab=all" class="settings-nav-link <?php echo ($tab === 'all') ? 'active' : ''; ?>">
                    All
                </a>

                <a href="?tab=pending" class="settings-nav-link <?php echo ($tab === 'pending') ? 'active' : ''; ?>">
                    Pending
                </a>

                <a href="?tab=accepted" class="settings-nav-link <?php echo ($tab === 'accepted') ? 'active' : ''; ?>">
                    Accepted
                </a>

                <a href="?tab=rejected" class="settings-nav-link <?php echo ($tab === 'rejected') ? 'active' : ''; ?>">
                    Rejected
                </a>

            </div>

            <!-- GRID -->
            <div class="stats-grid">



                <?php if (empty($demandes)): ?>
                <div class="card">
                    <p>No demandes found.</p>
                </div>
                <?php endif; ?>

                <?php foreach ($demandes as $d): ?>

                <div class="card" style="margin-bottom: 1.2rem;">

                    <!-- HEADER -->
                    <div class="card-header" style="align-items: center;">

                        <div style="display:flex; align-items:center; gap:10px;">

                            <div class="user-avatar">
                                <?php if (!empty($d['image'])): ?>
                                <img src="../uploads/<?php echo $d['image']; ?>"
                                    style="width:100%; height:100%; object-fit:cover; border-radius:6px;">
                                <?php else: ?>
                                <?php echo strtoupper(substr($d['freelancer_name'], 0, 1)); ?>
                                <?php endif; ?>
                            </div>

                            <div>
                                <strong><?php echo $d['freelancer_name']; ?></strong><br>
                                <small><?php echo $d['email']; ?></small>
                            </div>

                        </div>

                        <!-- STATUS -->
                        <span class="badge 
            <?php 
                if ($d['status'] === 'accepted') echo 'badge-green';
                elseif ($d['status'] === 'rejected') echo 'badge-red';
                else echo 'badge-orange';
            ?>">
                            <?php echo ucfirst($d['status']); ?>
                        </span>

                    </div>

                    <div style="display:flex; justify-content: space-between; gap:4px; margin-top:10px;  margin-bottom:
                        5px;">
                        <div class="card-title" style="display:flex; align-items:center; gap:8px;">
                            Offer : <?php echo $d['offer_title']; ?>
                        </div>

                        <div class="card-subtitle" style="display:flex; align-items:center; gap:6px;">
                            <i class="fa fa-tag"></i>
                            <?php echo $d['category']; ?>
                        </div>
                    </div>

                    <!-- BODY -->
                    <div style="margin-top:10px;">

                        <!-- OFFER INFO -->
                        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:12px;">



                            <div style="display:flex; align-items:center; gap:6px;">
                                <span><strong>Price:</strong> <?php echo $d['price']; ?> DT</span>
                            </div>

                            <div style="display:flex; align-items:center; gap:6px;">
                                <span><strong>Delivery:</strong> <?php echo $d['delivery_time']; ?> days</span>
                            </div>

                        </div>



                        <!-- OPTIONAL: VIEW MORE BUTTON -->
                        <?php if (strlen($d['message']) > 150): ?>
                        <button onclick="this.previousElementSibling.style.maxHeight='none'; this.style.display='none';"
                            class="btn btn-ghost" style="margin-top:5px;">
                            View more
                        </button>
                        <?php endif; ?>

                    </div>

                </div>

                <?php endforeach; ?>

            </div>

    </div>

    </main>

    </div>
    <script src="../js/templatemo-daynight-script.js"></script>

</body>

</html>