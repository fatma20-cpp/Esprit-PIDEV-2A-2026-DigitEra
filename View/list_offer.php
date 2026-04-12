<?php
include '../Controller/OffreController.php';

$offerC = new OffreController();
$list = $offerC->afficheroffres();

// DELETE
if (isset($_GET['delete'])) {
    $offerC->supprimerOffer($_GET['delete']);
    header("Location: listOffer.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Offers List</title>
<style>
table { width:100%; border-collapse: collapse; }
th, td { border:1px solid #ccc; padding:10px; }
a { margin: 5px; }
</style>
</head>

<body>

<h2>Offers List</h2>

<a href="create_offer.php">Add Offer</a>

<table>
<tr>
    <th>Title</th>
    <th>Description</th>
    <th>Budget</th>
    <th>Actions</th>
</tr>

<?php foreach ($list as $offer) { ?>
<tr>
    <td><?php echo $offer['title']; ?></td>
    <td><?php echo $offer['description']; ?></td>
    <td><?php echo $offer['budget']; ?></td>
    <td>
        <a href="update_offer.php?id=<?php echo $offer['id_offre']; ?>">Edit</a>
        <a href="list_offer.php?delete=<?php echo $offer['id_offre']; ?>">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>