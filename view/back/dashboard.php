<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();
$formations = $controller->getFormations();

// COUNTS
$debutant = 0;
$intermediaire = 0;
$avance = 0;
$revenu = 0;

foreach($formations as $f){
    if($f['niveau']=="debutant") $debutant++;
    if($f['niveau']=="intermediaire") $intermediaire++;
    if($f['niveau']=="avance") $avance++;

    $revenu += $f['prix'];
}

$total = count($formations);
?>

<?php include 'layout/header.php'; ?>



<!-- HEADER -->
<div class="page-header">
    <h1 class="greeting">Dashboard</h1>
    <p class="greeting-sub">Gestion des formations</p>
</div>

<!-- STATS -->
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-label">Total Formations</div>
        <div class="stat-value"><?php echo $total; ?></div>
        <div class="stat-change positive">✔ données à jour</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Débutant</div>
        <div class="stat-value"><?php echo $debutant; ?></div>
        <div class="stat-change positive">niveau bas</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Intermédiaire</div>
        <div class="stat-value"><?php echo $intermediaire; ?></div>
        <div class="stat-change positive">niveau moyen</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Avancé</div>
        <div class="stat-value"><?php echo $avance; ?></div>
        <div class="stat-change positive">niveau élevé</div>
    </div>

</div>

<!-- TWO COLUMN -->
<div class="two-col">

    <!-- CHART -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Répartition par niveau</h3>
                <p class="card-subtitle">Statistiques formations</p>
            </div>
        </div>

        <!-- ✅ USE TEMPLATE CLASS -->
        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Formations</h3>
        </div>

        <!-- ✅ USE TEMPLATE TABLE WRAPPER -->
        <div class="table-container">
            <table>
                <tr>
                    <th>Titre</th>
                    <th>Domaine</th>
                    <th>Niveau</th>
                    <th>Prix</th>
                </tr>

                <?php foreach($formations as $f): ?>
                <tr>
                    <td><?php echo $f['titre']; ?></td>
                    <td><?php echo $f['domaine']; ?></td>

                    <!-- ✅ NICE BADGE -->
                    <td>
                        <span class="badge 
                        <?php 
                            if($f['niveau']=='debutant') echo 'badge-green';
                            elseif($f['niveau']=='intermediaire') echo 'badge-blue';
                            else echo 'badge-orange';
                        ?>">
                            <?php echo $f['niveau']; ?>
                        </span>
                    </td>

                    <td><?php echo $f['prix']; ?> TND</td>
                </tr>
                <?php endforeach; ?>

            </table>
        </div>
    </div>

</div>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Débutant', 'Intermédiaire', 'Avancé'],
        datasets: [{
            label: 'Formations',
            data: [
                <?php echo $debutant; ?>,
                <?php echo $intermediaire; ?>,
                <?php echo $avance; ?>
            ],
            backgroundColor: ['#38BDF8','#0EA5E9','#0284C7'],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                labels: {
                    color: '#64748B'
                }
            }
        }
    }
});
</script>

<?php include 'layout/footer.php'; ?>