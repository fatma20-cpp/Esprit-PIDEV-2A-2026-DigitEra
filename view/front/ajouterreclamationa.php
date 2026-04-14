<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réclamation Client</title>
    <script>
        // Prevent flash of white in dark mode
        if (localStorage.getItem('daynight-theme') === 'carbon') {
            document.documentElement.classList.add('carbon');
        }
    </script>

    <!-- CSS TEMPLATE -->
    <link rel="stylesheet" href="/service_client/assets/css/templatemo-daynight-style.css">
    <style>
/* Sidebar - Cohérent avec le template */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100vh;
    background: var(--bg-primary);
    border-right: 1px solid var(--border-color);
    padding-top: 80px;
    z-index: 999;
    overflow-y: auto;
}

.sidebar h3 {
    color: var(--text-primary);
    text-align: center;
    padding: 20px 15px 10px 15px;
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    border-bottom: 1px solid var(--border-color);
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin: 0;
}

.sidebar ul li a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-secondary);
    text-decoration: none;
    padding: 12px 15px;
    transition: var(--transition);
    border-left: 3px solid transparent;
    font-size: 14px;
    font-weight: 500;
}

.sidebar ul li a:hover {
    background: var(--bg-surface);
    color: var(--text-primary);
    border-left-color: var(--accent);
}

.sidebar ul li a.active {
    background: var(--accent-light);
    color: var(--accent);
    border-left-color: var(--accent);
}

/* Ajuster le corps basé sur la sidebar */
body {
    padding-left: 250px !important;
}

/* Formulaires - Cohérent avec le template */
input, select, textarea {
    background: var(--bg-primary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    padding: 10px 12px;
    margin-top: 5px;
    border-radius: 8px;
    font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 0.9375rem;
    width: 100%;
    transition: var(--transition);
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-light);
}

input::placeholder,
textarea::placeholder {
    color: var(--text-secondary);
    font-style: italic;
}

button {
    background: var(--accent);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    width: 100%;
    transition: var(--transition);
    font-size: 0.9375rem;
}

button:hover {
    background: var(--accent-hover);
}

.container {
    background: var(--bg-primary);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow);
    margin: 30px;
    max-width: 600px;
}

.container h2 {
    color: var(--text-primary);
    border-bottom: 2px solid var(--accent);
    padding-bottom: 15px;
    margin-bottom: 25px;
    font-size: 1.5rem;
    font-weight: 700;
}

label {
    color: var(--text-primary);
    font-weight: 500;
    font-size: 14px;
}

/* Messages d'erreur et succès */
#errorMessages {
    background: rgba(239, 68, 68, 0.05);
    border-left: 3px solid var(--danger);
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
}

#errorMessages p {
    color: var(--danger);
    font-weight: 600;
    margin: 0;
}

#errorMessages ul {
    margin: 8px 0 0 0;
    padding-left: 20px;
    color: var(--danger);
}

#errorMessages li {
    margin: 5px 0;
}

#successBox {
    background: rgba(34, 197, 94, 0.05);
    border-left: 3px solid var(--success);
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
}

#successBox p {
    color: var(--success);
    margin: 0;
    font-weight: 600;
}

@media (max-width: 768px) {
    .sidebar {
        width: 200px;
    }
    
    body {
        padding-left: 200px !important;
    }
    
    .container {
        margin: 20px;
    }
}

/* Theme Toggle */
.theme-toggle {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-left: auto;
}

.theme-btn {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    width: 36px;
    height: 36px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.theme-btn:hover {
    background: var(--bg-primary);
    border-color: var(--accent);
}

.theme-btn.active {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
}

.theme-btn svg {
    width: 18px;
    height: 18px;
}

#navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 18px;
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


<body style="padding-top:80px;">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>📋 Menu Client</h3>
    <ul>
        <li><a href="ajouterreclamationa.php" class="active"><span>➕</span> Ajouter Réclamation</a></li>
        <li><a href="mes_reclamations.php"><span>📋</span> Mes Réclamations</a></li>
        <li><a href="#"><span>⚙️</span> Paramètres</a></li>
        <li><a href="#"><span>❓</span> Aide</a></li>
    </ul>
</div>

<!-- NAVBAR -->
<nav id="navbar">
    <div class="nav-container">
        <a href="ajouterreclamationa.php" class="logo">
            <img src="../../assets/images/logo.jpeg" alt="Service Client Logo" class="logo-img">
            <span class="logo-text">Service Client</span>
        </a>
    </div>
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
</nav>

<!-- ESPACE -->

<!-- FORMULAIRE CORRIGÉ -->
<div class="container" style="max-width:600px; margin:auto;">

    <h2>Faire une réclamation</h2>

    <div id="errorMessages" style="display:none;">
        <p>⚠️ Erreur(s):</p>
        <ul id="errorList"></ul>
    </div>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo "<div id='successBox'>";
        echo "<p>" . $_SESSION['success_message'] . "</p>";
        echo "</div>";
        unset($_SESSION['success_message']);
        unset($_SESSION['reclamation_id']);
    }

    if (isset($_SESSION['client_id'])) {
        echo "<div style='background: rgba(34, 197, 94, 0.05); border-left: 3px solid var(--success); padding: 15px; margin-bottom: 20px; border-radius: 8px;'>";
        echo "<p style='color: var(--success); margin: 0; font-weight: 600;'>📋 Votre ID Client: <strong>" . $_SESSION['client_id'] . "</strong></p>";
        echo "</div>";
        
        // 🔹 Ajouter un bouton pour aller à Mes Réclamations
        echo "<div style='text-align: center; margin-bottom: 20px;'>";
        echo "<a href='mes_reclamations.php' style='background: var(--accent); color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; font-weight: 600; transition: var(--transition);' onmouseover=\"this.style.opacity='0.8'\" onmouseout=\"this.style.opacity='1'\">📋 Voir Mes Réclamations →</a>";
        echo "</div>";
    }
    ?>

    <form action="/service_client/controller/reclamationcontroller.php" method="POST">

        <label>Nom</label>
        <input type="text" name="nom" placeholder="Ex: Dupont (lettres seulement, max 20)" maxlength="20" oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, ''); validateNom();"><br><br>

        <label>Prénom</label>
        <input type="text" name="prenom" placeholder="Ex: Jean (lettres seulement, max 20)" maxlength="20" oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, ''); validatePrenom();"><br><br>

        <label>Email</label>
        <input type="text" name="email" placeholder="prenom.nom@domaine.com"><br><br>

        <label>Sujet</label>
        <input type="text" name="sujet" placeholder="Résumé court du problème (max 50 caractères)"><br><br>

        <label>Type de problème</label>
        <select name="type_probleme" required>
            <option value="">-- Sélectionner --</option>
            <option value="Service">Service</option>
            <option value="Bug">Bug</option>
        </select><br><br>

        <label>Message</label>
        <textarea name="message" rows="6" maxlength="200" placeholder="Décrivez votre réclamation en détail (max 200 caractères)" required></textarea><br><br>

        <button type="submit" name="ajouter">
            ✅ Envoyer la réclamation
        </button>

    </form>

</div>

<!-- FOOTER -->
<footer style="text-align:center; margin-top:50px;">
    <p>© 2026 Service Client - Tous droits réservés</p>
</footer>
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

// Validation en temps réel pour Nom
function validateNom() {
    let nomInput = document.querySelector("[name='nom']");
    let nom = nomInput.value.trim();
    
    // Bloquer les chiffres et caractères spéciaux
    nomInput.value = nomInput.value.replace(/[^a-zA-ZÀ-ÿ\s-]/g, '');
}

// Validation en temps réel pour Prénom
function validatePrenom() {
    let prenomInput = document.querySelector("[name='prenom']");
    let prenom = prenomInput.value.trim();
    
    // Bloquer les chiffres et caractères spéciaux
    prenomInput.value = prenomInput.value.replace(/[^a-zA-ZÀ-ÿ\s-]/g, '');
}

// Validation à la soumission du formulaire
document.querySelector("form").addEventListener("submit", function(e) {

    let nom = document.querySelector("[name='nom']").value.trim();
    let prenom = document.querySelector("[name='prenom']").value.trim();
    let email = document.querySelector("[name='email']").value.trim();
    let sujet = document.querySelector("[name='sujet']").value.trim();
    let message = document.querySelector("[name='message']").value.trim();
    let type = document.querySelector("[name='type_probleme']").value;

    let errors = [];

    let nameRegex = /^[a-zA-ZÀ-ÿ\s\-]+$/;
    let emailRegex = /^[a-zA-Z]+\.[a-zA-Z]+@[a-zA-Z]+\.[a-zA-Z]+$/;

    // 🔴 Vérifier les champs vides - PRIORITAIRE
    if (nom === "") {
        errors.push("❌ Le champ Nom est obligatoire");
    }

    if (prenom === "") {
        errors.push("❌ Le champ Prénom est obligatoire");
    }

    if (email === "") {
        errors.push("❌ Le champ Email est obligatoire");
    }

    if (sujet === "") {
        errors.push("❌ Le champ Sujet est obligatoire");
    }

    if (type === "") {
        errors.push("❌ Veuillez sélectionner un Type de problème");
    }

    if (message === "") {
        errors.push("❌ Le champ Message est obligatoire");
    }

    // 🔵 Vérifier les formats si les champs ne sont pas vides
    if (nom && !nameRegex.test(nom)) {
        errors.push("❌ Nom invalide (lettres seulement)");
    }

    if (prenom && !nameRegex.test(prenom)) {
        errors.push("❌ Prénom invalide (lettres seulement)");
    }

    if (nom && nom.length > 20) {
        errors.push("❌ Nom max 20 caractères");
    }

    if (prenom && prenom.length > 20) {
        errors.push("❌ Prénom max 20 caractères");
    }

    if (sujet && sujet.length > 50) {
        errors.push("❌ Sujet max 50 caractères");
    }

    if (message && message.length > 200) {
        errors.push("❌ Message max 200 caractères");
    }

    if (email && !emailRegex.test(email)) {
        errors.push("❌ Email invalide (prenom.nom@domaine.com)");
    }

    if (errors.length > 0) {
        e.preventDefault();
        
        // Vider les champs
        document.querySelector("[name='nom']").value = "";
        document.querySelector("[name='prenom']").value = "";
        document.querySelector("[name='email']").value = "";
        document.querySelector("[name='sujet']").value = "";
        document.querySelector("[name='message']").value = "";
        document.querySelector("[name='type_probleme']").value = "";
        
        // Afficher les erreurs dans la boîte grise
        let errorDiv = document.getElementById("errorMessages");
        let errorList = document.getElementById("errorList");
        errorList.innerHTML = "";
        
        errors.forEach(function(error) {
            let li = document.createElement("li");
            li.textContent = error;
            errorList.appendChild(li);
        });
        
        errorDiv.style.display = "block";
        errorDiv.scrollIntoView({ behavior: "smooth", block: "nearest" });
    }

});
</script>

<!-- JS TEMPLATE -->
<script src="/service_client/assets/js/templatemo-daynight-script.js"></script>

<script>
// Si un message de succès est affiché, vider le formulaire après 2 secondes
if (document.getElementById('successBox')) {
    setTimeout(function() {
        document.querySelector("form").reset();
        document.getElementById("errorMessages").style.display = "none";
    }, 2000);
}
</script>

</body>
</html>