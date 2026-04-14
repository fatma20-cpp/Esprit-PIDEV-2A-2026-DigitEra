<?php
session_start();
require_once "../../config/database.php";
require_once "../../model/Reclamation.php";

$id = $_GET['id'] ?? null;
$mode = $_GET['mode'] ?? null;

if (!$id) {
    header("Location: liste_reclamation.php");
    exit;
}

$database = new Database();
$conn = $database->connect();
$model = new Reclamation($conn);

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repondre'])) {
    $reponse = trim($_POST['reponse'] ?? '');
    
    if ($reponse === '') {
        $_SESSION['error_message'] = "❌ La réponse ne peut pas être vide";
    } else {
        if ($model->saveResponse($id, $reponse)) {
            $_SESSION['success_message'] = "✅ Réponse envoyée avec succès!";
            header("Location: consulter_reclamation.php?id=$id");
            exit;
        } else {
            $_SESSION['error_message'] = "❌ Erreur lors de l'envoi de la réponse";
        }
    }
}

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
    <title>Consulter Réclamation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fa;
            padding: 30px;
            min-height: 100vh;
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
            font-weight: 500;
        }

        .back-btn:hover {
            background: #2d3748;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card h2 {
            color: #2d3748;
            margin-bottom: 20px;
            text-align: center;
        }

        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            padding: 15px;
            background: #f7fafc;
            border-radius: 8px;
        }

        .info-label {
            font-weight: 700;
            color: #667eea;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .info-value {
            color: #2d3748;
            font-size: 16px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .message-box {
            background: white;
            border: 2px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-wrap;
            word-wrap: break-word;
            min-height: 100px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #2d3748;
        }

        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            resize: vertical;
            min-height: 150px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #2d3748;
            flex: 1;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .message-success {
            background: #c6f6d5;
            color: #22543d;
            border-left: 4px solid #48bb78;
        }

        .message-error {
            background: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #f56565;
        }

        .response-section {
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
            border: 2px solid #22c55e;
            border-radius: 10px;
            padding: 25px;
            margin-top: 20px;
        }

        .response-box {
            background: #f0fdf4;
            border: 2px solid #22c55e;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .response-box h4 {
            color: #166534;
            margin-bottom: 10px;
        }

        .response-content {
            color: #4a5568;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.6;
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
        <div class="message message-success">
            <?= $_SESSION['success_message'] ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message message-error">
            <?= $_SESSION['error_message'] ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Détails de la Réclamation - <?= htmlspecialchars($reclamation['id_reclamation']) ?></h2>

        <div class="info-row">
            <div class="info-item">
                <div class="info-label">Nom</div>
                <div class="info-value"><?= htmlspecialchars($reclamation['nom']) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Prénom</div>
                <div class="info-value"><?= htmlspecialchars($reclamation['prenom']) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value"><?= htmlspecialchars($reclamation['email']) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Type</div>
                <div class="info-value"><?= htmlspecialchars($reclamation['type_probleme']) ?></div>
            </div>
            <div class="info-item full-width">
                <div class="info-label">Sujet</div>
                <div class="info-value"><?= htmlspecialchars($reclamation['sujet']) ?></div>
            </div>
            <div class="info-item full-width">
                <div class="info-label">Message</div>
                <div class="message-box"><?= htmlspecialchars($reclamation['message']) ?></div>
            </div>
        </div>

        <?php if ($mode === 'repondre'): ?>
            <div class="response-section">
                <h3>✉️ Envoyer une Réponse</h3>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                    <input type="hidden" name="repondre" value="1">
                    
                    <div class="form-group">
                        <label for="reponse">Votre Réponse:</label>
                        <textarea id="reponse" name="reponse" required placeholder="Écrivez votre réponse au client..."></textarea>
                    </div>

                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">✈️ Envoyer</button>
                        <a href="consulter_reclamation.php?id=<?= htmlspecialchars($id) ?>" class="btn btn-secondary">✕ Annuler</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="buttons">
                <a href="consulter_reclamation.php?id=<?= htmlspecialchars($id) ?>&mode=repondre" class="btn btn-primary">✉️ Répondre</a>
                <a href="liste_reclamation.php" class="btn btn-secondary">← Retour</a>
            </div>
        <?php endif; ?>

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
