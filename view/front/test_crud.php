<?php
require_once "../../config/database.php";
require_once "../../model/Reclamation.php";
session_start();

// Connexion BD
$database = new Database();
$conn = $database->connect();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test CRUD Réclamations</title>
    <style>
        body { font-family: Arial; max-width: 1000px; margin: 50px auto; padding: 20px; }
        .box { border: 1px solid #ddd; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        button { padding: 8px 15px; margin: 5px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        .actions { white-space: nowrap; }
    </style>
</head>
<body>

<h1>🧪 TEST CRUD - Gestion Réclamations</h1>

<div class="box info">
    <h3>📋 État de la Session:</h3>
    <?php
    if (isset($_SESSION['client_id'])) {
        echo "✅ Client ID: <strong>" . $_SESSION['client_id'] . "</strong>";
    } else {
        echo "❌ Pas de client_id en session";
    }
    ?>
</div>

<div class="box">
    <h3>1️⃣ Ajouter une Réclamation (CREATE)</h3>
    <form method="POST" action="../../controller/reclamationcontroller.php">
        <p>
            <label>Nom:</label><br>
            <input type="text" name="nom" value="Martin" required>
        </p>
        <p>
            <label>Prénom:</label><br>
            <input type="text" name="prenom" value="Jean" required>
        </p>
        <p>
            <label>Email:</label><br>
            <input type="text" name="email" value="jean.martin@example.com" required>
        </p>
        <p>
            <label>Sujet:</label><br>
            <input type="text" name="sujet" value="Test Réclamation" required>
        </p>
        <p>
            <label>Type:</label><br>
            <select name="type_probleme" required>
                <option value="">-- Sélectionner --</option>
                <option value="Service">Service</option>
                <option value="Bug">Bug</option>
            </select>
        </p>
        <p>
            <label>Message:</label><br>
            <textarea name="message" rows="4" required>Ceci est un test de réclamation pour vérifier que le CRUD fonctionne correctement.</textarea>
        </p>
        <button type="submit" name="ajouter">✅ Ajouter Réclamation</button>
    </form>
</div>

<div class="box">
    <h3>2️⃣ Afficher les Réclamations (READ)</h3>
    <?php
    if (!isset($_SESSION['client_id'])) {
        echo "<div class='error'>❌ Veuillez d'abord ajouter une réclamation pour voir cette section</div>";
    } else {
        $model = new Reclamation($conn);
        $reclamations = $model->readByClientId($_SESSION['client_id']);
        
        if (count($reclamations) > 0) {
            echo "<button onclick=\"location.reload()\">🔄 Rafraîchir</button>";
            echo "<table>";
            echo "<tr><th>N°</th><th>Sujet</th><th>Type</th><th>Message</th><th>Actions</th></tr>";
            
            foreach ($reclamations as $rec) {
                echo "<tr>";
                echo "<td><strong>" . $rec['id_reclamation'] . "</strong></td>";
                echo "<td>" . htmlspecialchars($rec['sujet']) . "</td>";
                echo "<td>" . htmlspecialchars($rec['type_probleme']) . "</td>";
                echo "<td>" . substr(htmlspecialchars($rec['message']), 0, 30) . "...</td>";
                echo "<td class='actions'>";
                echo "<button onclick=\"location.href='consulter_reclamation.php?id=" . urlencode($rec['id_reclamation']) . "'\">👁️ Voir</button> ";
                echo "<button onclick=\"location.href='modifier_reclamation.php?id=" . urlencode($rec['id_reclamation']) . "'\">✏️ Modifier</button> ";
                echo "<button onclick=\"if(confirm('Supprimer?')) location.href='../../controller/reclamationcontroller.php?action=delete&id=" . urlencode($rec['id_reclamation']) . "'\">🗑️ Supprimer</button>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='error'>❌ Aucune réclamation trouvée</div>";
        }
    }
    ?>
</div>

<div class="box info">
    <h3>📝 Instructions de Test:</h3>
    <ol>
        <li><strong>Ajouter une réclamation:</strong> Remplissez le formulaire et cliquez "Ajouter Réclamation"</li>
        <li><strong>Vérifier la création:</strong> Un client_id doit être généré et affiché</li>
        <li><strong>Voir les données:</strong> Vérifiez que la réclamation apparaît dans le tableau</li>
        <li><strong>Modifier:</strong> Cliquez "Modifier", changez des données, sauvegardez</li>
        <li><strong>Supprimer:</strong> Cliquez "Supprimer" et confirmez</li>
        <li><strong>Allez sur "Mes Réclamations":</strong> <a href="mes_reclamations.php">mes_reclamations.php</a></li>
    </ol>
</div>

<div class="box" style="text-align: center;">
    <a href="ajouterreclamationa.php" style="padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 10px;">➕ Ajouter Réclamation</a>
    <a href="mes_reclamations.php" style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 4px; margin: 10px;">📋 Mes Réclamations</a>
</div>

</body>
</html>
