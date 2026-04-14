<?php
require_once '../../controller/FormationController.php';

$controller = new FormationController();

// 🔐 Check ID
if(!isset($_GET['id'])){
    die("ID manquant");
}

// 🔍 Get formation
$formation = $controller->getFormationById($_GET['id']);

// ❌ If not found
if(!$formation){
    die("Formation introuvable");
}
?>

<?php include 'layout/header.php'; ?>

<div class="main-content">

<h1 style="text-align:center;">Modifier Formation</h1>

<div class="card" style="max-width:600px; margin:auto;">

<form method="POST" action="updateFormationAction.php" onsubmit="return validateForm()">

    <input type="hidden" name="id" value="<?php echo $formation['id']; ?>">

    <!-- Titre -->
    <div class="form-group">
        <label class="form-label">Titre</label>
        <input type="text" class="form-input" id="titre" name="titre"
        value="<?php echo htmlspecialchars($formation['titre']); ?>"
        oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">
        <small id="errorTitre" class="error"></small>
    </div>

    <!-- Description -->
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-input" id="description" name="description"><?php echo htmlspecialchars($formation['description']); ?></textarea>
        <small id="errorDescription" class="error"></small>
    </div>

    <!-- Domaine -->
    <div class="form-group">
        <label class="form-label">Domaine</label>
        <input type="text" class="form-input" id="domaine" name="domaine"
        value="<?php echo htmlspecialchars($formation['domaine']); ?>"
        oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">
        <small id="errorDomaine" class="error"></small>
    </div>

    <!-- Niveau -->
    <div class="form-group">
        <label class="form-label">Niveau</label>
        <select class="form-input" id="niveau" name="niveau">
            <option value="">-- Choisir niveau --</option>
            <option value="debutant" <?php if($formation['niveau']=="debutant") echo "selected"; ?>>Débutant</option>
            <option value="intermediaire" <?php if($formation['niveau']=="intermediaire") echo "selected"; ?>>Intermédiaire</option>
            <option value="avance" <?php if($formation['niveau']=="avance") echo "selected"; ?>>Avancé</option>
        </select>
        <small id="errorNiveau" class="error"></small>
    </div>

    <!-- Prix -->
    <div class="form-group">
        <label class="form-label">Prix (TND)</label>
        <input type="number" class="form-input" id="prix" name="prix"
        value="<?php echo htmlspecialchars($formation['prix']); ?>">
        <small id="errorPrix" class="error"></small>
    </div>

    <!-- Durée -->
    <div class="form-group">
        <label class="form-label">Durée</label>
        <input type="text" class="form-input" id="duree" name="duree"
        value="<?php echo htmlspecialchars($formation['duree']); ?>">
        <small id="errorDuree" class="error"></small>
    </div>

    <!-- Instructor -->
    <div class="form-group">
        <label class="form-label">Instructor</label>
        <input type="text" class="form-input" id="instructor" name="instructor"
        value="<?php echo htmlspecialchars($formation['instructor']); ?>"
        oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">
        <small id="errorInstructor" class="error"></small>
    </div>

    <!-- BUTTON -->
    <button class="btn btn-primary" style="width:100%; margin-top:10px;">
        Modifier
    </button>

</form>

</div>
</div>

<!-- ✅ VALIDATION -->
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

    // reset errors
    document.getElementById("errorTitre").innerText = "";
    document.getElementById("errorDescription").innerText = "";
    document.getElementById("errorDomaine").innerText = "";
    document.getElementById("errorNiveau").innerText = "";
    document.getElementById("errorPrix").innerText = "";
    document.getElementById("errorDuree").innerText = "";
    document.getElementById("errorInstructor").innerText = "";

    let regex = /^[A-Za-zÀ-ÿ\s]+$/;

    // titre
    if(titre === ""){
        document.getElementById("errorTitre").innerText = "Veuillez remplir ce champ";
        valid = false;
    } else if(!regex.test(titre)){
        document.getElementById("errorTitre").innerText = "Seulement des lettres";
        valid = false;
    }

    // description
    if(description === ""){
        document.getElementById("errorDescription").innerText = "Veuillez remplir ce champ";
        valid = false;
    }

    // domaine
    if(domaine === ""){
        document.getElementById("errorDomaine").innerText = "Veuillez remplir ce champ";
        valid = false;
    } else if(!regex.test(domaine)){
        document.getElementById("errorDomaine").innerText = "Seulement des lettres";
        valid = false;
    }

    // niveau
    if(niveau === ""){
        document.getElementById("errorNiveau").innerText = "Veuillez choisir un niveau";
        valid = false;
    }

    // prix
    if(prix === "" || prix <= 0){
        document.getElementById("errorPrix").innerText = "Prix invalide";
        valid = false;
    }

    // duree
    if(duree === ""){
        document.getElementById("errorDuree").innerText = "Veuillez remplir la durée";
        valid = false;
    }

    // instructor
    if(instructor === ""){
        document.getElementById("errorInstructor").innerText = "Veuillez remplir ce champ";
        valid = false;
    } else if(!regex.test(instructor)){
        document.getElementById("errorInstructor").innerText = "Seulement des lettres";
        valid = false;
    }

    return valid;
}
</script>

<?php include 'layout/footer.php'; ?>