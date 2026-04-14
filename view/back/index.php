<?php
require_once '../../config/database.php';
require_once '../../model/Reclamation.php';

$db = (new Database())->connect();
$reclamation = new Reclamation($db);
$liste = $reclamation->read();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Réclamations</title>
    <script>
        // Prevent flash of white in dark mode
        if (localStorage.getItem('daynight-theme') === 'carbon') {
            document.documentElement.classList.add('carbon');
        }
    </script>

    <!-- CSS TEMPLATE -->
    <link rel="stylesheet" href="../../assets/css/templatemo-daynight-style.css">
</head>

<body>

<div class="app-container">

    <!-- 🔵 NAVBAR -->
    <nav class="top-nav">
        <div class="nav-container">
            <div class="nav-left">
                <a href="dashboard.php" class="logo-link">
                    <img src="../../assets/images/logo.jpeg" alt="Service Client Logo" class="logo-img">
                    <span class="logo-text">Service Client</span>
                </a>

                <!-- ✅ NAVIGATION FIXÉE -->
                <div class="nav-menu">
                    <a href="dashboard.php" class="nav-link">Accueil</a>
                    <a href="index.php" class="nav-link active">Réclamations</a>
                </div>
            </div>
            <div class="nav-right">
                <div class="theme-toggle">
                    <button class="theme-btn theme-btn-snow active" onclick="setTheme('snow')" title="Mode Clair">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                    </button>
                    <button class="theme-btn theme-btn-carbon" onclick="setTheme('carbon')" title="Mode Sombre">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <style>
        .logo-link {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.1rem;
            transition: opacity 0.3s ease;
        }

        .logo-link:hover {
            opacity: 0.8;
        }

        .logo-img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .logo-text {
            color: var(--text-primary);
        }

        .nav-menu {
            display: flex;
            gap: 20px;
            margin-left: 30px;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
            padding: 8px 0;
        }

        .nav-link:hover {
            color: var(--accent);
        }

        .nav-link.active {
            color: var(--accent);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--accent);
        }
    </style>

    <!-- 🔵 CONTENU -->
    <main class="main-content">

        <div class="page-header">
            <h1 class="greeting">Gestion des Réclamations</h1>
            <p class="greeting-sub">Liste complète des réclamations</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des Réclamations</h3>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Sujet</th>
                            <th>message</th>
                            <th>type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($liste as $row): ?>
                        <tr>
                            <td><?= $row['nom'] ?></td>
                            <td><?= $row['sujet'] ?></td>
                            <!-- ✅ message -->
                            <td><?= $row['message'] ?></td>

                            <td>
                                <span class="badge badge-blue">
                                    <?= $row['type_probleme'] ?>
                                </span>
                            </td>

                            <td>
                                <a class="btn btn-secondary"
                                href="consulter_reclamation.php?id=<?= $row['id_reclamation'] ?>">
                                Consulter
                                </a>

                                <a class="btn btn-primary"
                                href="consulter_reclamation.php?id=<?= $row['id_reclamation'] ?>&mode=repondre">
                                Répondre
                                </a>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>

    </main>
</div>

<!-- JS TEMPLATE -->
<script src="../../assets/js/templatemo-daynight-script.js"></script>

<script>
// Theme toggle
function setTheme(theme) {
    localStorage.setItem('daynight-theme', theme);
    if (theme === 'carbon') {
        document.documentElement.classList.add('carbon');
        document.body.classList.add('carbon');
    } else {
        document.documentElement.classList.remove('carbon');
        document.body.classList.remove('carbon');
    }
    updateThemeButtons();
}

function updateThemeButtons() {
    const theme = localStorage.getItem('daynight-theme') || 'snow';
    document.querySelectorAll('.theme-btn-snow').forEach(btn => {
        btn.classList.toggle('active', theme === 'snow');
    });
    document.querySelectorAll('.theme-btn-carbon').forEach(btn => {
        btn.classList.toggle('active', theme === 'carbon');
    });
}

// Initialize theme buttons on page load
window.addEventListener('load', updateThemeButtons);
</script>

</body>
</html>