<?php
session_start();
include "../../config/database.php";
include "../../model/Reclamation.php";

// 🔹 Initialiser la connexion à la base de données
$database = new Database();
$conn = $database->connect();

$reclamationModel = new Reclamation($conn);
$reclamations = $reclamationModel->read();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Réclamations - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .header h1 {
            color: #2d3748;
            font-size: 28px;
        }

        .header p {
            color: #718096;
            font-size: 14px;
            margin-top: 5px;
        }

        .stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .message-success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #22c55e;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            color: white;
        }

        th {
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px 18px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }

        tbody tr {
            transition: 0.3s;
        }

        tbody tr:hover {
            background: #f7fafc;
        }

        .id-cell {
            font-weight: 700;
            color: #667eea;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-responded {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn-small {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view {
            background: #e0f2fe;
            color: #0369a1;
        }

        .btn-view:hover {
            background: #06b6d4;
            color: white;
            transform: translateY(-2px);
        }

        .btn-reply {
            background: #f0fdf4;
            color: #166534;
        }

        .btn-reply:hover {
            background: #22c55e;
            color: white;
            transform: translateY(-2px);
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }

        .no-data-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            th, td {
                padding: 12px 8px;
                font-size: 12px;
            }

            .actions {
                flex-direction: column;
            }

            .btn-small {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
<div class="container">
    <div class="header">
        <div>
            <h1>📋 Réclamations Client</h1>
            <p>Gérez toutes les demandes et réponses</p>
        </div>
        <div class="stats">
            Total: <?= count($reclamations) ?> réclamations
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message-success">
            <?= $_SESSION['success_message'] ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (count($reclamations) > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>N° Réclamation</th>
                        <th>Nom Client</th>
                        <th>Email</th>
                        <th>Sujet</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reclamations as $row): ?>
                        <tr>
                            <td class="id-cell"><?= htmlspecialchars($row['id_reclamation']) ?></td>
                            <td><?= htmlspecialchars($row['nom'] . ' ' . $row['prenom']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><strong><?= htmlspecialchars(substr($row['sujet'], 0, 30)) ?></strong><?= strlen($row['sujet']) > 30 ? '...' : '' ?></td>
                            <td><span style="background: #eef2ff; color: #667eea; padding: 4px 8px; border-radius: 4px; font-size: 12px;"><?= htmlspecialchars($row['type_probleme']) ?></span></td>
                            <td>
                                <?php if (!empty($row['reponse'])): ?>
                                    <span class="status-badge status-responded">✅ Répondu</span>
                                <?php else: ?>
                                    <span class="status-badge status-pending">⏳ En Attente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="consulter_reclamation.php?id=<?= htmlspecialchars($row['id_reclamation']) ?>" class="btn-small btn-view">
                                        👁 Consulter
                                    </a>
                                    <a href="consulter_reclamation.php?id=<?= htmlspecialchars($row['id_reclamation']) ?>&mode=repondre" class="btn-small btn-reply">
                                        ✉️ Répondre
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="table-container">
            <div class="no-data">
                <div class="no-data-icon">📭</div>
                <h3>Aucune réclamation</h3>
                <p>Il n'y a pas encore de réclamations à traiter</p>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>