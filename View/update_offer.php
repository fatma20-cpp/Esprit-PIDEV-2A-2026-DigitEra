<?php
require_once '../Controller/OffreController.php';
require_once '../Model/Offre.php';

$offerC = new OffreController();

$error = "";
$success = "";

// GET CURRENT DATA
if (isset($_GET['id'])) {
    $offer = $offerC->recupererOffer($_GET['id']);
}

// UPDATE
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
        if (!is_numeric($_POST["budget"])) {
            $error = "Budget must be a number";
        } else {

            $newOffer = new Offre(
                $_POST['title'],
                $_POST['description'],
                $_POST['budget'],
                1
            );

            $offerC->modifierOffer($newOffer, $_GET['id']);
            $success = "Offer updated successfully";
        }
    } else {
        $error = "Missing fields";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Update Offer</title>
<style>
body { font-family: Arial; padding: 40px; }
input, textarea { width: 100%; padding: 10px; margin: 10px 0; }
button { padding: 10px; background: black; color: white; }
.error { color: red; }
.success { color: green; }
</style>
</head>

<body>

<h2>Update Offer</h2>

<?php if ($error != "") echo "<p class='error'>$error</p>"; ?>
<?php if ($success != "") echo "<p class='success'>$success</p>"; ?>

<form method="POST">

<input type="text" name="title" value="<?= $offer['title'] ?>">
<textarea name="description"><?= $offer['description'] ?></textarea>
<input type="text" name="budget" value="<?= $offer['budget'] ?>">

<button type="submit">Update</button>

</form>

<a href="list_offer.php">Back</a>

</body>
</html>