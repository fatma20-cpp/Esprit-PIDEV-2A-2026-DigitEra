<?php
require_once '../Controller/OffreController.php';
require_once '../Model/Offre.php';

$error = "";
$success = "";

if (
    isset($_POST["title"]) &&
    isset($_POST["description"]) &&
    isset($_POST["budget"])
) {
    if (
        !empty($_POST["title"]) &&
        !empty($_POST["description"]) &&
        !empty($_POST["budget"])
    ) {
        // VALIDATION (NOT HTML5 ✅)
        if (!is_numeric($_POST["budget"])) {
            $error = "Budget must be a number";
        } else {

            $offer = new Offre(
                $_POST['title'],
                $_POST['description'],
                $_POST['budget'],
                1 // company_id (temporary)
            );

            $offerC = new OffreController();
            $offerC->ajouterOffer($offer);

            $success = "Offer added successfully";
        }
    } else {
        $error = "Missing information";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Offer</title>
    <style>
        body { font-family: Arial; padding: 40px; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px; background: black; color: white; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>

<body>

<h2>Add Offer</h2>

<?php if ($error != "") echo "<p class='error'>$error</p>"; ?>
<?php if ($success != "") echo "<p class='success'>$success</p>"; ?>

<form method="POST">

    <input type="text" name="title" placeholder="Title">
    
    <textarea name="description" placeholder="Description"></textarea>
    
    <input type="text" name="budget" placeholder="Budget">

    <button type="submit">Add Offer</button>

</form>

<a href="list_offer.php">View Offers</a>

</body>
</html>