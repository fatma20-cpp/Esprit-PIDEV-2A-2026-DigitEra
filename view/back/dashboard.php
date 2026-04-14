<?php
session_start();
require_once '../../config/database.php';
require_once '../../model/Reclamation.php';

$db = (new Database())->connect();
$reclamation = new Reclamation($db);

$toutes_reclamations = $reclamation->read();

// Calculs des statistiques
$total_reclamations = count($toutes_reclamations);
$reclamations_service = 0;
$reclamations_bug = 0;

foreach ($toutes_reclamations as $r) {
    if (strtolower($r['type_probleme']) == 'service') {
        $reclamations_service++;
    } else if (strtolower($r['type_probleme']) == 'bug') {
        $reclamations_bug++;
    }
}

$taux_service = $total_reclamations > 0 ? round(($reclamations_service / $total_reclamations) * 100) : 0;
$taux_bug = $total_reclamations > 0 ? round(($reclamations_bug / $total_reclamations) * 100) : 0;

// Dernières réclamations
$dernieres_reclamations = array_slice($toutes_reclamations, -5);
$dernieres_reclamations = array_reverse($dernieres_reclamations);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Système de Réclamations</title>
    <script>
        // Prevent flash of white in dark mode - runs before CSS/page render
        if (localStorage.getItem('daynight-theme') === 'carbon') {
            document.documentElement.classList.add('carbon');
        }
    </script>
    <link rel="stylesheet" href="../../assets/css/templatemo-daynight-style.css">
    <style>
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.1rem;
            transition: opacity 0.3s ease;
        }

        .logo:hover {
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
    </style>
</head>
<body>
    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay"></div>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <a href="dashboard.php" class="logo">
                <img src="../../assets/images/logo.jpeg" alt="Service Client Logo" class="logo-img">
                <span class="logo-text">Service Client</span>
            </a>
            <button class="mobile-menu-close" onclick="closeMobileMenu()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <nav class="mobile-menu-nav">
            <a href="dashboard.php" class="active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                Accueil
            </a>
            <a href="index.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                </svg>
                Réclamations
            </a>
            <a href="../front/ajouterreclamationa.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Ajouter
            </a>
        </nav>
        <div class="mobile-menu-footer">
            <a href="#" class="mobile-logout-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Déconnexion
            </a>
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

    <div class="app-container">
        <!-- Top Navigation -->
        <nav class="top-nav">
            <div class="nav-container">
                <div class="nav-left">
                    <a href="dashboard.php" class="logo">
                        <img src="../../assets/images/logo.jpeg" alt="Service Client Logo" class="logo-img">
                        <span class="logo-text">Service Client</span>
                    </a>
                    <div class="nav-menu">
                        <div class="nav-item">
                            <a href="dashboard.php" class="nav-link active">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                                </svg>
                                Accueil
                            </a>
                        </div>
                        <div class="nav-item">
                            <a href="index.php" class="nav-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                </svg>
                                Réclamations
                            </a>
                        </div>
                        <div class="nav-item">
                            <a href="../front/ajouterreclamationa.php" class="nav-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Ajouter Réclamation
                            </a>
                        </div>
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
                    <button class="user-menu">
                        <div class="user-avatar">A</div>
                        <span class="user-name">Admin</span>
                    </button>
                    <a href="#" class="btn-logout" title="Déconnexion">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </a>
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="greeting">📊 Dashboard Réclamations</h1>
                <p class="greeting-sub">Gérez et analysez toutes les réclamations clients en temps réel.</p>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Réclamations</div>
                    <div class="stat-value"><?= $total_reclamations ?></div>
                    <div class="stat-change positive">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                            <polyline points="17 6 23 6 23 12"/>
                        </svg>
                        📋 Tous les types
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Type Service</div>
                    <div class="stat-value"><?= $reclamations_service ?></div>
                    <div class="stat-change positive">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                            <polyline points="17 6 23 6 23 12"/>
                        </svg>
                        <?= $taux_service ?>% du total
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Type Bug</div>
                    <div class="stat-value"><?= $reclamations_bug ?></div>
                    <div class="stat-change positive">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                            <polyline points="17 6 23 6 23 12"/>
                        </svg>
                        <?= $taux_bug ?>% du total
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Ratio Service/Bug</div>
                    <div class="stat-value"><?= $total_reclamations > 0 && $reclamations_bug > 0 ? round(($reclamations_service / $reclamations_bug) * 100, 1) : '0' ?>%</div>
                    <div class="stat-change positive">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                            <polyline points="17 6 23 6 23 12"/>
                        </svg>
                        Équilibre
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="two-col">
                <!-- Chart Réclamations par Type -->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Répartition par Type</h3>
                            <p class="card-subtitle">Comparaison Service vs Bug</p>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div class="chart-scroll">
                            <div class="chart-scroll-inner">
                                <div class="bar-chart">
                                    <div class="y-axis">
                                        <span class="y-axis-label"><?= $total_reclamations ?></span>
                                        <span class="y-axis-label"><?= round($total_reclamations * 0.75) ?></span>
                                        <span class="y-axis-label"><?= round($total_reclamations * 0.5) ?></span>
                                        <span class="y-axis-label"><?= round($total_reclamations * 0.25) ?></span>
                                        <span class="y-axis-label">0</span>
                                    </div>
                                    <div class="y-axis-lines">
                                        <div class="y-axis-line"></div>
                                        <div class="y-axis-line"></div>
                                        <div class="y-axis-line"></div>
                                        <div class="y-axis-line"></div>
                                        <div class="y-axis-line"></div>
                                    </div>
                                    <div class="bar-group">
                                        <div class="bar-wrapper">
                                            <div class="bar current" style="height: <?= $total_reclamations > 0 ? ($reclamations_service / $total_reclamations) * 100 : 0 ?>px; background: var(--success);"></div>
                                        </div>
                                        <span class="bar-label">Service</span>
                                    </div>
                                    <div class="bar-group">
                                        <div class="bar-wrapper">
                                            <div class="bar current" style="height: <?= $total_reclamations > 0 ? ($reclamations_bug / $total_reclamations) * 100 : 0 ?>px; background: #EF4444;"></div>
                                        </div>
                                        <span class="bar-label">Bug</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <span class="legend-dot" style="background: var(--success);"></span>
                                Service (<?= $reclamations_service ?>)
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot" style="background: #EF4444;"></span>
                                Bug (<?= $reclamations_bug ?>)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Complaints -->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Dernières Réclamations</h3>
                            <p class="card-subtitle">Les 5 plus récentes</p>
                        </div>
                        <a href="index.php" class="btn btn-ghost">Voir tout</a>
                    </div>
                    <div class="card-scroll">
                        <div class="card-scroll-inner" style="min-width: 360px;">
                            <div class="activity-feed">
                                <?php if(count($dernieres_reclamations) > 0): ?>
                                    <?php foreach ($dernieres_reclamations as $rec): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon <?= strtolower($rec['type_probleme']) == 'bug' ? 'blue' : 'green' ?>">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <polyline points="14 2 14 8 20 8"/>
                                                <line x1="16" y1="13" x2="8" y2="13"/>
                                                <line x1="16" y1="17" x2="8" y2="17"/>
                                            </svg>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-text"><strong><?= $rec['nom'] ?> <?= $rec['prenom'] ?></strong> - <?= $rec['sujet'] ?></p>
                                            <span class="activity-time"><?= $rec['type_probleme'] ?> | <?= $rec['id_reclamation'] ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                                        <p>Aucune réclamation pour le moment</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Détaillées -->
            <div class="two-col" style="margin-top: 1.5rem;">
                <!-- Métriques -->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Métriques Clés</h3>
                            <p class="card-subtitle">Indicateurs de performance</p>
                        </div>
                    </div>
                    <div class="card-scroll">
                        <div class="card-scroll-inner" style="min-width: 400px;">
                            <div style="padding: 0.5rem 0;">
                                <div style="margin-bottom: 1.5rem;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.875rem; color: var(--text-primary);">Taux Service</span>
                                        <span style="font-size: 0.875rem; font-weight: 600; color: var(--success);"><?= $taux_service ?>%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill success" style="width: <?= $taux_service ?>%;"></div>
                                    </div>
                                </div>
                                <div style="margin-bottom: 1.5rem;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.875rem; color: var(--text-primary);">Taux Bug</span>
                                        <span style="font-size: 0.875rem; font-weight: 600; color: #EF4444;"><?= $taux_bug ?>%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="background: #EF4444; width: <?= $taux_bug ?>%;"></div>
                                    </div>
                                </div>
                                <div style="margin-bottom: 1.5rem;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.875rem; color: var(--text-primary);">Gestion</span>
                                        <span style="font-size: 0.875rem; font-weight: 600; color: var(--accent);">100%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill accent" style="width: 100%;"></div>
                                    </div>
                                </div>
                                <div style="margin-bottom: 1.5rem;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.875rem; color: var(--text-primary);">Efficacité</span>
                                        <span style="font-size: 0.875rem; font-weight: 600; color: var(--success);">95%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill success" style="width: 95%;"></div>
                                    </div>
                                </div>
                                <div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.875rem; color: var(--text-primary);">Disponibilité</span>
                                        <span style="font-size: 0.875rem; font-weight: 600; color: var(--success);">99.9%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill success" style="width: 99.9%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Infos Dashboard -->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Informations Système</h3>
                            <p class="card-subtitle">État du système de réclamations</p>
                        </div>
                    </div>
                    <div class="card-scroll">
                        <div class="card-scroll-inner" style="min-width: 350px;">
                            <div style="padding: 1rem; color: var(--text-primary); font-size: 0.875rem; line-height: 1.8;">
                                <p><strong>Version:</strong> 1.0.0</p>
                                <p><strong>Base de données:</strong> MySQL Active</p>
                                <p><strong>Statut:</strong> <span style="color: var(--success);">✓ Opérationnel</span></p>
                                <p><strong>Dernière mise à jour:</strong> Aujourd'hui</p>
                                <p><strong>Utilisateurs actifs:</strong> 1</p>
                                <hr style="border: none; border-top: 1px solid var(--border-color); margin: 1rem 0;">
                                <p style="color: var(--text-secondary); font-size: 0.8rem;">Système de gestion des réclamations clients avec dashboard analytique en temps réel.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; 2026 Système de Réclamations. Tous droits réservés.</p>
        </footer>
    </div>

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

        // Mobile menu
        function toggleMobileMenu() {
            document.querySelector('.mobile-menu-overlay').classList.toggle('active');
            document.querySelector('.mobile-menu').classList.toggle('active');
        }

        function closeMobileMenu() {
            document.querySelector('.mobile-menu-overlay').classList.remove('active');
            document.querySelector('.mobile-menu').classList.remove('active');
        }

        // Initialize
        updateThemeButtons();
    </script>
</body>
</html>