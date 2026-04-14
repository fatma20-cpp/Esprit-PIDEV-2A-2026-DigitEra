<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();
$formations = $controller->getFormations();
?>

<?php include 'layout/header.php'; ?>

<div class="main-content">

<h1>Gestion des Formations</h1>

<?php if(isset($_GET['success'])): ?>
<p style="color:green;">Opération réussie ✔</p>
<?php endif; ?>

<div class="card">

<table>

<tr>
    <th>ID</th>
    <th>Titre</th>
    <th>Domaine</th>
    <th>Niveau</th>
    <th>Prix</th>
    <th>Durée</th>
    <th>Instructor</th>
    <th>Actions</th>
</tr>

<?php foreach($formations as $f): ?>
<tr>

<td><?php echo $f['id']; ?></td>
<td><?php echo $f['titre']; ?></td>
<td><?php echo $f['domaine']; ?></td>
<td><?php echo $f['niveau']; ?></td>

<td style="color:#cf397a; font-weight:bold;">
    <?php echo $f['prix']; ?> TND
</td>

<td><?php echo $f['duree']; ?></td>
<td><?php echo $f['instructor']; ?></td>

<td>
    <a href="editFormation.php?id=<?php echo $f['id']; ?>"
       style="text-decoration: underline; color:#096996;">
        Modifier
    </a>
    |
    <a href="deleteFormation.php?id=<?php echo $f['id']; ?>"
       onclick="return confirm('Supprimer cette formation ?')"
       style="text-decoration: underline; color:red;">
        Supprimer
    </a>
</td>

</tr>
<?php endforeach; ?>

</table>

</div>

</div>

<?php include 'layout/footer.php'; ?>