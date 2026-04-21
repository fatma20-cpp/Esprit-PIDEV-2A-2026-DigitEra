<?php

// 🔥 DEBUG (remove later)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../Controller/OffreController.php';
require_once '../Controller/DemandeController.php';
require_once '../Model/Demande.php';

$offerC = new OffreController();
$demandeC = new DemandeController();

// ✅ CHECK ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No offer selected");
}

// ✅ GET OFFER
$offer = $offerC->recupererOffer($_GET['id']);

// ✅ CHECK IF EXISTS
if (!$offer) {
    die("Offer not found");
}

$errors = [
    "price" => "",
    "delivery_time" => "",
    "message" => ""
];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $price = $_POST["price"] ?? "";
    $delivery = $_POST["delivery_time"] ?? "";
    $message = $_POST["message"] ?? "";
   

    $isValid = true;

// VALIDATION
if (empty($price) || !is_numeric($price)) {
     $errors["price"] = "Valid price is required";
    $isValid = false;
}

if (empty($delivery) || !is_numeric($delivery)) {
    $errors["delivery_time"] = "Valid delivery time is required";
    $isValid = false;
}

if (empty($message)) {
    $errors["message"] = "Message is required";
    $isValid = false;
}

if ($isValid) {

    $demande = new Demande(
        $_POST['id_offer'],
        3, // HARDCODED FREELANCER ID
        $price,
        $delivery,
        $message
    );

    $demandeC->ajouterDemande($demande);

    header("Location: index.php#offers");
    exit();
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Apply to Offer</title>

    <link rel="stylesheet" href="../css/templatemo-graph-page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
    .container {
        max-width: 850px;
        margin: 120px auto 50px;
        padding: 20px;
    }

    .offer-preview {
        background: #0f172a;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        color: white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .offer-preview h3 {
        margin-bottom: 10px;
    }

    .offer-meta {
        margin-top: 15px;
        display: flex;
        gap: 20px;
        font-size: 14px;
        opacity: 0.9;
    }

    .form-box {
        background: #ffffff;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    textarea {
        width: 100%;
        height: 120px;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        resize: none;
    }

    .error-text {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }

    .btn-submit {
        margin-top: 20px;
        background: linear-gradient(45deg, #00c6ff, #0072ff);
        border: none;
        padding: 12px 25px;
        color: white;
        border-radius: 10px;
        cursor: pointer;
    }

    .btn-submit:hover {
        opacity: 0.9;
    }

    .back-btn {
        display: inline-block;
        margin-bottom: 15px;
        color: #0072ff;
        text-decoration: none;
    }
    </style>
</head>

<body>
    <nav id="navbar">
        <div class="nav-container">

            <a href="index.php#home" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 13h2v8H3zm4-8h2v13H7zm4-2h2v15h-2zm4 4h2v11h-2zm4-2h2v13h-2z" />
                    </svg>
                </div>
                <span class="logo-text">DigitEra</span>
            </a>

            <ul class="nav-links">
                <li><a href="index.php#home">Home</a></li>
                <li><a href="index.php#dashboard">Dashboard</a></li>
                <li><a href="index.php#freelancers">Freelancers</a></li>
                <li><a href="index.php#offers">Offers</a></li>
                <li><a href="index.php#contact">Contact</a></li>
                <li><a href="./login.html">Log in</a></li>
            </ul>

            <a href="index.php#home" title="Search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </a>

            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>

        </div>

        <ul class="nav-links-mobile" id="navLinksMobile">
            <li><a href="index.php#home">Home</a></li>
            <li><a href="index.php#dashboard">Dashboard</a></li>
            <li><a href="index.php#freelancers">Freelancers</a></li>
            <li><a href="index.php#offers">Offers</a></li>
            <li><a href="index.php#contact">Contact</a></li>
        </ul>
    </nav>
    <div class="main-content">

        <!-- HEADER -->
        <div class="page-header">
            <a href="index.php#offers" class="btn btn-ghost">
                <i class="fa-solid fa-arrow-left"></i> Back to Offers
            </a>

            <h2 class="greeting">Apply to Offer</h2>
            <p class="greeting-sub">Submit your proposal to this project</p>
        </div>

        <!-- GRID LAYOUT -->
        <div class="apply-container">

            <!-- LEFT → OFFER -->
            <div class="apply-offer">

                <div class="offer-box">

                    <h3><?php echo htmlspecialchars($offer['title']); ?></h3>

                    <p class="offer-desc">
                        <?php echo htmlspecialchars($offer['description']); ?>
                    </p>

                    <div class="offer-meta">
                        <span><i class="fa fa-money-bill"></i> <?php echo $offer['budget']; ?> DT</span>
                        <span><i class="fa fa-tag"></i> <?php echo $offer['category']; ?></span>
                        <span><i class="fa fa-calendar"></i> <?php echo $offer['deadline']; ?></span>
                    </div>

                </div>

            </div>


            <!-- RIGHT → FORM -->
            <div class="apply-form">

                <h2 class="apply-title">Submit Proposal</h2>

                <form method="POST">

                    <input type="hidden" name="id_offer" value="<?php echo $offer['id_offre']; ?>">

                    <div class="form-group">
                        <label>Proposed Price (DT)</label>
                        <input type="number" name="price" placeholder="Enter your price">
                        <div class="error-text"><?php echo $errors["price"]; ?></div>
                    </div>

                    <div class="form-group">
                        <label>Delivery Time (days)</label>
                        <input type="number" name="delivery_time" placeholder="e.g. 5">
                        <div class="error-text"><?php echo $errors["delivery_time"]; ?></div>

                    </div>

                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" placeholder="Explain why you are the best fit..."></textarea>
                        <div class="error-text"><?php echo $errors["message"]; ?></div>

                    </div>


                    <button type="submit" class="btn-submit">
                        <i class="fa fa-paper-plane"></i> Apply Now
                    </button>

                </form>

            </div>

        </div>

    </div>

    </div>

</body>

</html>