<?php
require_once '../Controller/OffreController.php';
require_once '../Model/Offre.php';

$errors = [
    "title" => "",
    "description" => "",
    "category" => "",
    "budget" => "",
    "deadline" => ""
];

$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST["title"] ?? "";
    $description = $_POST["description"] ?? "";
    $category = $_POST["category"] ?? "";
    $budget = $_POST["budget"] ?? "";
    $deadline = $_POST["deadline"] ?? "";

    $isValid = true;

    // TITLE
    if (empty($title)) {
        $errors["title"] = "Title is required";
        $isValid = false;
    }

    // DESCRIPTION
    if (empty($description)) {
        $errors["description"] = "Description is required";
        $isValid = false;
    }

    // CATEGORY
    if (empty($category)) {
        $errors["category"] = "Category is required";
        $isValid = false;
    }

    // BUDGET
    if (empty($budget)) {
        $errors["budget"] = "Budget is required";
        $isValid = false;
    } elseif (!is_numeric($budget) || $budget <= 0) {
        $errors["budget"] = "Budget must be a positive number";
        $isValid = false;
    }

    // DEADLINE
    if (empty($deadline)) {
        $errors["deadline"] = "Deadline is required";
        $isValid = false;
    } elseif (strtotime($deadline) < time()) {
        $errors["deadline"] = "Deadline must be in the future";
        $isValid = false;
    }

    if ($isValid) {

    $offer = new Offre(
        $title,
        $description,
        $budget,
         1// company_id
    );

    $offer->setCategory($category);
    $offer->setDeadline($deadline);

    $offerC = new OffreController();
    $offerC->ajouterOffer($offer);

    // 🔥 REDIRECT AFTER SUCCESS
    header("Location: ListOffer.php");
    exit();
}
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Create Offer</title>
    <link rel="stylesheet" href="../css/templatemo-daynight-style.css">

    <style>
    .error-text {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
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
                    <a href="ListOffer.php" class="nav-link active"><svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
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



        <!-- FORM -->
        <main class="main-content">

            <div class="page-header">
                <div style="margin-bottom:15px;">
                    <a href="ListOffer.php" class="btn btn-ghost">
                        ← Back to Offers
                    </a>
                </div>
                <h1 class="greeting">Create Offer</h1>
                <p class="greeting-sub">Fill all fields to publish your project</p>


            </div>

            <div class="offer-grid">

                <div class="card">

                    <h2 class="settings-title">Offer Details</h2>
                    <p class="settings-desc">All fields are required</p>

                    <?php if ($success != "") { ?>
                    <div class="success-box"><?php echo $success; ?></div>
                    <?php } ?>

                    <form method="POST">

                        <div class="create-offer-from">
                            <div class="row">
                                <div class="form-group">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-input"
                                        value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                                    <div class="error-text"><?php echo $errors["title"]; ?></div>
                                </div>

                                <!-- DESCRIPTION -->
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-input"
                                        style="min-height:160px;"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                    <div class="error-text"><?php echo $errors["description"]; ?></div>
                                </div>

                            </div> <!-- TITLE -->


                            <div class="row">
                                <!-- CATEGORY -->

                                <!-- BUDGET -->
                                <div class="form-group">
                                    <label class="form-label">Budget (DT)</label>
                                    <input type="text" name="budget" class="form-input"
                                        value="<?php echo htmlspecialchars($_POST['budget'] ?? ''); ?>">
                                    <div class="error-text"><?php echo $errors["budget"]; ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-input">
                                        <option value="">Select category</option>
                                        <option value="DEV">Development</option>
                                        <option value="UI">UI/UX</option>
                                        <option value="MARKETING">Marketing</option>
                                        <option value="DATA">Data</option>
                                    </select>
                                    <div class="error-text"><?php echo $errors["category"]; ?></div>
                                </div>

                                <!-- DEADLINE -->
                                <div class="form-group">
                                    <label class="form-label">Deadline</label>
                                    <input type="date" name="deadline" class="form-input"
                                        value="<?php echo htmlspecialchars($_POST['deadline'] ?? ''); ?>">
                                    <div class="error-text"><?php echo $errors["deadline"]; ?></div>
                                </div>
                            </div>


                        </div>




                        <div>
                            <!-- BUTTON -->
                            <div style="margin-top:20px; display:flex; gap:10px;">

                                <button type="submit" class="btn btn-primary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                        <path d="M12 5v14M5 12h14" />
                                    </svg>
                                    Create Offer
                                </button>

                                <a href="ListOffer.php" class="btn btn-ghost">Cancel</a>

                            </div>
                        </div>


                    </form>

                </div>

            </div>

        </main>


    </div>
    <script src="../js/templatemo-daynight-script.js"></script>

</body>

</html>