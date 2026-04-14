<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réclamation Client</title>

    <!-- CSS TEMPLATE -->
    <link rel="stylesheet" href="/service_client/assets/css/templatemo-daynight-style.css">
    <style>
input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background: #4CAF50;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;

    width: 100%;
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    background: #45a049;
}

.container {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
body {
    margin: 0;
}

/* Style des placeholders en gris */
input::placeholder,
textarea::placeholder {
    color: #999;
    font-style: italic;
}

input::-webkit-input-placeholder,
textarea::-webkit-input-placeholder {
    color: #999;
    font-style: italic;
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

<!-- NAVBAR -->
<nav id="navbar">
    <div class="nav-container">
        <a href="ajouterreclamation.php" class="logo">
            <img src="../../assets/images/logo.jpeg" alt="Service Client Logo" class="logo-img">
            <span class="logo-text">Service Client</span>
        </a>

        <ul class="nav-links">
            <li><a href="ajouterreclamation.php">Accueil</a></li>
            <li><a href="#" class="active">Réclamation</a></li>
        </ul>
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

    <div id="errorMessages" style="display:none; background: #f0f0f0; border-left: 4px solid #ff6b6b; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        <p style="margin: 0; color: #333; font-weight: bold;">⚠️ Erreur(s):</p>
        <ul id="errorList" style="margin: 10px 0 0 0; color: #555; padding-left: 20px;"></ul>
    </div>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo "<div id='successBox' style='background: #e8f5e9; border-left: 4px solid #4CAF50; padding: 15px; margin-bottom: 20px; border-radius: 5px;'>";
        echo "<p style='margin: 0; color: #2e7d32; font-weight: bold; font-size: 16px;'>" . $_SESSION['success_message'] . "</p>";
        echo "</div>";
        unset($_SESSION['success_message']);
        unset($_SESSION['reclamation_id']);
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
        <select name="type_probleme">
            <option value=""></option>
            <option value="Service">Service</option>
            <option value="Bug">Bug</option>
        </select><br><br>

        <label>Message</label>
        <textarea name="message" placeholder="Décrivez votre réclamation en détail (max 200 caractères)"></textarea><br><br>

        <button type="submit" name="ajouter">
            Envoyer la réclamation
        </button>

    </form>

</div>

<!-- FOOTER -->
<footer style="text-align:center; margin-top:50px;">
    <p>© 2026 Service Client - Tous droits réservés</p>
</footer>
<script>
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