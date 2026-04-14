<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Ajouter Formation</title>

<link rel="stylesheet" href="../back/template/templatemo-daynight-style.css">

<style>
body {
    background: #f5f7fb;
    font-family: Arial;
    margin: 0;
}

.top-nav {
    background: #FFFFFF;
    padding: 15px 30px;
}

.nav-container {
    display: flex;
    justify-content: space-between;
}

.logo {
    color: #096996;
    font-weight: bold;
    text-decoration: none;
}

.nav-link {
    color: white;
    margin-left: 20px;
    text-decoration: none;
}

.main-content {
    padding: 30px;
}

.error {
        color: var(--danger);
        font-size: 13px;
        margin-top: 5px;
    }


</style>
</head>

<body>

<!-- ✅ FRONT NAVBAR -->
<nav class="top-nav">
    <div class="nav-container">
        <a href="index.php" class="logo">My Platform</a>

        <div>
            <a href="index.php" class="nav-link">Home</a>
        </div>
    </div>
</nav>

<div class="main-content">

<div class="card" style="max-width:600px; margin:auto;">

<h2 style="text-align:center;">Ajouter Formation</h2>

<form method="POST" action="addFormationAction.php" onsubmit="return validateForm()">

    <!-- TITRE -->
    <div class="form-group">
        <label class="form-label">Titre</label>
        <input type="text" class="form-input" id="titre" name="titre"
        oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">
        <small id="errorTitre" class="error"></small>
    </div>

    <!-- DESCRIPTION -->
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-input" id="description" name="description"></textarea>
        <small id="errorDescription" class="error"></small>
    </div>

    <!-- DOMAINE -->
    <div class="form-group">
        <label class="form-label">Domaine</label>
        <input type="text" class="form-input" id="domaine" name="domaine"
        oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">
        <small id="errorDomaine" class="error"></small>
    </div>

    <!-- NIVEAU -->
    <div class="form-group">
        <label class="form-label">Niveau</label>
        <select class="form-input" id="niveau" name="niveau">
            <option value="">-- Choisir niveau --</option>
            <option value="debutant">Débutant</option>
            <option value="intermediaire">Intermédiaire</option>
            <option value="avance">Avancé</option>
        </select>
        <small id="errorNiveau" class="error"></small>
    </div>

    <!-- PRIX -->
    <div class="form-group">
        <label class="form-label">Prix (TND)</label>
        <input type="number" class="form-input" id="prix" name="prix">
        <small id="errorPrix" class="error"></small>
    </div>

    <!-- DUREE -->
    <div class="form-group">
        <label class="form-label">Durée</label>
        <input type="text" class="form-input" id="duree" name="duree">
        <small id="errorDuree" class="error"></small>
    </div>

    <!-- INSTRUCTOR -->
    <div class="form-group">
        <label class="form-label">Instructor</label>
        <input type="text" class="form-input" id="instructor" name="instructor"
        oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">
        <small id="errorInstructor" class="error"></small>
    </div>

    <button class="btn btn-primary" style="width:100%;">
        Ajouter
    </button>

</form>

</div>

</div>

<!-- VALIDATION -->
<script>
function validateForm(){

    let titre = document.getElementById("titre").value.trim();
    let description = document.getElementById("description").value.trim();
    let domaine = document.getElementById("domaine").value.trim();
    let niveau = document.getElementById("niveau").value;
    let prix = document.getElementById("prix").value;
    let duree = document.getElementById("duree").value.trim();
    let instructor = document.getElementById("instructor").value.trim();

    let valid = true;

    document.getElementById("errorTitre").innerText = "";
    document.getElementById("errorDescription").innerText = "";
    document.getElementById("errorDomaine").innerText = "";
    document.getElementById("errorNiveau").innerText = "";
    document.getElementById("errorPrix").innerText = "";
    document.getElementById("errorDuree").innerText = "";
    document.getElementById("errorInstructor").innerText = "";

    let regex = /^[A-Za-zÀ-ÿ\s]+$/;

    if(titre === ""){
        document.getElementById("errorTitre").innerText = "Champ obligatoire";
        valid = false;
    } else if(!regex.test(titre)){
        document.getElementById("errorTitre").innerText = "Seulement des lettres";
        valid = false;
    }

    if(description === ""){
        document.getElementById("errorDescription").innerText = "Champ obligatoire";
        valid = false;
    }

    if(domaine === ""){
        document.getElementById("errorDomaine").innerText = "Champ obligatoire";
        valid = false;
    } else if(!regex.test(domaine)){
        document.getElementById("errorDomaine").innerText = "Seulement des lettres";
        valid = false;
    }

    if(niveau === ""){
        document.getElementById("errorNiveau").innerText = "Choisir un niveau";
        valid = false;
    }

    if(prix === "" || prix <= 0){
        document.getElementById("errorPrix").innerText = "Prix invalide";
        valid = false;
    }

    if(duree === ""){
        document.getElementById("errorDuree").innerText = "Champ obligatoire";
        valid = false;
    }

    if(instructor === ""){
        document.getElementById("errorInstructor").innerText = "Champ obligatoire";
        valid = false;
    } else if(!regex.test(instructor)){
        document.getElementById("errorInstructor").innerText = "Seulement des lettres";
        valid = false;
    }

    return valid;
}
</script>

</body>
</html>