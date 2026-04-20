<?php
include '../Controller/OffreController.php';

$offerC = new OffreController();

// 🔥 DELETE FIX
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    if ($offerC->supprimerOffer($id)) {
        header("Location: ListOffer.php");
        exit();
    } else {
        echo "Delete failed";
    }
}

$list = $offerC->afficherOffreParCompanyId('1');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Offers</title>
    <link rel="stylesheet" href="../css/templatemo-daynight-style.css">
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

                    <div class="nav-menu">
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
                            <a href="projects.html" class="nav-link"><svg viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                                </svg>Projects</a>
                        </div>
                        <div class="nav-item">
                            <a href="inbox.html" class="nav-link"><svg viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>Inbox</a>
                        </div>
                        <div class="nav-item">
                            <a href="analytics.html" class="nav-link"><svg viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10" />
                                    <line x1="12" y1="20" x2="12" y2="4" />
                                    <line x1="6" y1="20" x2="6" y2="14" />
                                </svg>Analytics</a>
                        </div>
                        <div class="nav-item">
                            <a href="ListOffer.php" class="nav-link active"><svg viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
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
                            <a href="settings.html" class="nav-link"><svg viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3" />
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                </svg>Settings</a>
                        </div>
                    </div>
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

        <!-- MAIN -->
        <main class="main-content">

            <!-- HEADER -->
            <div class="page-header-offers">
                <div>
                    <h1 class="greeting">Offers - Company 1</h1>
                    <p class="greeting-sub">Manage your published offers</p>
                </div>

                <a href="CreateOffer.php" class="btn btn-primary">
                    + Add Offer
                </a>
            </div>

            <!-- GRID -->
            <div class="stats-grid">

                <?php foreach ($list as $offer) { ?>

                <div class="card">

                    <div class="stat-header">
                        <div class="stat-icon">
                            <!-- BOX ICON -->
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="7" width="18" height="13" rx="2" />
                                <path d="M16 3v4M8 3v4" />
                            </svg>
                        </div>

                        <div class="stat-title">
                            <?php echo htmlspecialchars($offer['title']); ?>
                        </div>
                    </div>

                    <div class="stat-category">
                        <?php echo htmlspecialchars($offer['category'] ?? 'General'); ?>
                    </div>

                    <div class="stat-description">
                        <?php echo htmlspecialchars($offer['description']); ?>
                    </div>

                    <div class="offer-meta">

                        <!-- BUDGET -->
                        <div class="meta-line">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 7v10M9 10h6" />
                            </svg>
                            <strong><?php echo $offer['budget']; ?> DT</strong>
                        </div>

                        <!-- DEADLINE -->
                        <div class="meta-line">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 7v5l3 3" />
                            </svg>
                            <span class="deadline">
                                <?php echo $offer['deadline'] ?? '-'; ?>
                            </span>
                        </div>

                        <!-- CREATED -->
                        <div class="meta-line">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="16" rx="2" />
                                <path d="M16 3v4M8 3v4" />
                            </svg>
                            <?php echo $offer['created_at'] ?? '-'; ?>
                        </div>

                    </div>

                    <!-- ACTIONS -->
                    <div style="margin-top:20px; display:flex; gap:10px;">

                        <a href="UpdateOffer.php?id=<?php echo $offer['id_offre']; ?>" class="btn btn-secondary">
                            Edit
                        </a>

                        <a href="ListOffer.php?delete=<?php echo urlencode($offer['id_offre']); ?>"
                            class="btn btn-danger" onclick="return confirm('Delete this offer?');">
                            Delete
                        </a>

                    </div>

                </div>

                <?php } ?>

            </div>

        </main>

    </div>
    <script src="../js/templatemo-daynight-script.js"></script>

</body>

</html>