<?php
require_once "../../config/database.php";
require_once "../../model/Reclamation.php";
session_start();

// 🔹 Récupérer l'ID de la réclamation
$id = $_GET['id'] ?? null;
$mode = $_GET['mode'] ?? null;

if (!$id) {
    header("Location: liste_reclamation.php");
    exit;
}

// 🔹 Créer la connexion et récupérer les détails
$database = new Database();
$conn = $database->connect();
$model = new Reclamation($conn);

// 🔹 Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repondre'])) {
    $reponse = trim($_POST['reponse'] ?? '');
    
    if ($reponse === '') {
        $_SESSION['error_message'] = "❌ La réponse ne peut pas être vide";
    } else {
        if ($model->saveResponse($id, $reponse)) {
            $_SESSION['success_message'] = "✅ Réponse envoyée avec succès!";
            header("Location: consulter.php?id=$id");
            exit;
        } else {
            $_SESSION['error_message'] = "❌ Erreur lors de l'envoi de la réponse";
        }
    }
}

// 🔹 Récupérer les détails de la réclamation
$reclamation = $model->getById($id);

if (!$reclamation) {
    header("Location: liste_reclamation.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Réclamation - Partie Admin</title>
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
            max-width: 900px;
            margin: 0 auto;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #4a5568;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
            font-weight: 500;
        }

        .back-btn:hover {
            background: #2d3748;
            transform: translateY(-2px);
        }

        .reclamation-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        .card-header h2 {
            color: #2d3748;
            font-size: 28px;
        }

        .card-header p {
            color: #718096;
            font-size: 14px;
            margin-top: 5px;
        }

        .id-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .id-badge small {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .id-badge strong {
            display: block;
            font-size: 24px;
            font-weight: 700;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .info-item {
            padding: 15px;
            background: #f7fafc;
            border-radius: 8px;
            border-left: 3px solid #667eea;
        }

        .info-item.full {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 12px;
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 16px;
            color: #2d3748;
            font-weight: 500;
        }

        .message-box {
            background: white;
            border: 2px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.8;
            color: #4a5568;
            margin-top: 10px;
            min-height: 120px;
        }

        .response-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #06b6d4;
            border-radius: 12px;
            padding: 30px;
            margin-top: 30px;
        }

        .response-section h3 {
            color: #0369a1;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3748;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 14px;
            resize: vertical;
            min-height: 150px;
            transition: 0.3s;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
            flex: 1;
        }

        .btn-secondary:hover {
            background: #f7fafc;
            transform: translateY(-2px);
        }

        .message-success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #22c55e;
        }

        .message-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ef4444;
        }

        .response-box {
            background: white;
            border: 2px solid #dcfce7;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #22c55e;
        }

        .response-box h4 {
            color: #166534;
            margin-bottom: 10px;
        }

        .response-content {
            color: #4a5568;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.8;
        }

        .response-date {
            color: #718096;
            font-size: 12px;
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>

<body>
<div class="container">
    <a href="liste_reclamation.php" class="back-btn">← Retour</a>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message-success">
            <?= $_SESSION['success_message'] ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message-error">
            <?= $_SESSION['error_message'] ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="reclamation-card">
        <div class="card-header">
            <div>
                <h2>Détails de la Réclamation</h2>
                <p>Consultez et gérez la demande de ce client</p>
            </div>
            <div class="id-badge">
                <small>N° Réclamation</small>
                <strong><?= htmlspecialchars($reclamation['id_reclamation']) ?></strong>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">👤 Nom</div>
                <div class="info-value"><?= htmlspecialchars($reclamation['nom']) ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">👥 Prénom</div>
                <div class="info-value"><?= htmlspecialchars($reclamation['prenom']) ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">📧 Email</div>
                <div class="info-value"><a href="mailto:<?= htmlspecialchars($reclamation['email']) ?>" style="color: #667eea; text-decoration: none;"><?= htmlspecialchars($reclamation['email']) ?></a></div>
            </div>

            <div class="info-item">
                <div class="info-label">🏷️ Type</div>
                <div class="info-value" style="display: inline-block; background: #eef2ff; color: #667eea; padding: 4px 10px; border-radius: 4px;"><?= htmlspecialchars($reclamation['type_probleme']) ?></div>
            </div>

            <div class="info-item full">
                <div class="info-label">💬 Sujet</div>
                <div class="info-value"><?= htmlspecialchars($reclamation['sujet']) ?></div>
            </div>

            <div class="info-item full">
                <div class="info-label">📝 Message</div>
                <div class="message-box"><?= htmlspecialchars($reclamation['message']) ?></div>
            </div>
        </div>

        <?php if ($mode === 'repondre'): ?>
            <!-- FORMULAIRE DE RÉPONSE -->
            <div class="response-section">
                <h3>✉️ Envoyer une Réponse</h3>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                    <input type="hidden" name="repondre" value="1">

                    <div class="form-group">
                        <label for="reponse">Votre Réponse:</label>
                        <textarea id="reponse" name="reponse" required placeholder="Écrivez votre réponse au client..."></textarea>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <span>✈️</span> Envoyer la Réponse
                        </button>
                        <a href="consulter.php?id=<?= htmlspecialchars($id) ?>" class="btn btn-secondary">
                            <span>✕</span> Annuler
                        </a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- BOUTON RÉPONDRE -->
            <div class="action-buttons" style="margin-top: 30px;">
                <a href="consulter.php?id=<?= htmlspecialchars($id) ?>&mode=repondre" class="btn btn-primary">
                    <span>✉️</span> Répondre
                </a>
                <a href="liste_reclamation.php" class="btn btn-secondary">
                    <span>←</span> Retour
                </a>
            </div>
        <?php endif; ?>

        <!-- AFFICHER LA RÉPONSE SI ELLE EXISTE -->
        <?php if (!empty($reclamation['reponse'])): ?>
            <div class="response-box">
                <h4>✅ Réponse Envoyée</h4>
                <div class="response-content"><?= htmlspecialchars($reclamation['reponse']) ?></div>
                <div class="response-date">📅 Le <?= !empty($reclamation['date_reponse']) ? date('d/m/Y à H:i', strtotime($reclamation['date_reponse'])) : 'Date non disponible' ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>