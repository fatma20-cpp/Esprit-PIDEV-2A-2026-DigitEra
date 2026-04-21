    <?php
    $pdo = new PDO("mysql:host=localhost;dbname=gestion_formation;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $view = $_GET['action'] ?? 'list';
    ?>
    <?php
// 🔥 HANDLE UPDATE BEFORE ANY HTML OUTPUT
if(isset($_GET['action']) && $_GET['action'] == 'updateCert'){

    $stmt = $pdo->prepare("
        UPDATE certificate 
        SET user_name=?, date_obtention=? 
        WHERE certificate_code=?
    ");

    $stmt->execute([
        $_POST['user_name'],
        $_POST['date_obtention'],
        $_POST['certificate_code']
    ]);

    header("Location: index.php?action=showCert&user_name=".$_POST['user_name']."&date=".$_POST['date_obtention']."&code=".$_POST['certificate_code']."&success=updated");
    exit;
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Graph Page - Modern Analytics Dashboard</title>
        <link rel="stylesheet" href="templatemo-graph-page.css">
    <!-- 

    TemplateMo 602 Graph Page

    https://templatemo.com/tm-602-graph-page

    -->
    </head>

    <body>
            <div id="bookingMessage" style="
        display:none;
        position:fixed;
        top:20px;
        left:50%;
        transform:translateX(-50%);
        background:#22c55e;
        color:white;
        padding:15px 25px;
        border-radius:10px;
        font-weight:bold;
        z-index:9999;
    ">
        ✔ Booking done successfully!
    </div>

    <div id="certificateForm" style="display:none; position:fixed; top:50%; left:50%;
    transform:translate(-50%,-50%); background:#111; padding:30px; border-radius:10px; z-index:1000;">

        <h3 style="color:white;">Enter your name</h3>

        <form method="POST" action="addCertificate.php">

            <input type="text" name="user_name" placeholder="Your name" required
                style="padding:10px; width:100%;">

            <!-- 🔥 VERY IMPORTANT -->
            <input type="hidden" name="formation_id" id="formationId">
            <input type="date" name="date_obtention" required>

            <br><br>

            <button type="button" class="cta-button" onclick="checkForm()">
                Generate
            </button>

        </form>
        <button class="cta-button" onclick="closeForm()">Cancel</button>

    </div>
    <div id="quizBox" style="
    display:none;
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    background:white;
    padding:25px;
    border-radius:10px;
    z-index:2000;
    text-align:center;
">

    <h3>Quick Question ❓</h3>

    <p>HTML is:</p>

    <select id="quizAnswer">
        <option value="">-- Choose --</option>
        <option value="wrong">Programming language</option>
        <option value="correct">Markup language</option>
        <option value="wrong2">Database</option>
    </select>

    <br><br>

    <button class="cta-button" onclick="validateQuiz()">Validate</button>
    <button class="cta-button" onclick="closeQuiz()">Cancel</button>

</div>

<script>
function openCertificateForm(id){
    document.getElementById("certificateForm").style.display = "block";
    document.getElementById("formationId").value = id;
}

function closeForm(){
    document.getElementById("certificateForm").style.display = "none";
}
</script>
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
    function toggleDetails(id){
        let div = document.getElementById("details-" + id);

        if(div.style.display === "none"){
            div.style.display = "block";
        } else {
            div.style.display = "none";
        }
    }
    </script>

        <!-- Navigation -->
        <nav id="navbar">
            <div class="nav-container">
                <a href="#home" class="logo">
                    <div class="logo-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 13h2v8H3zm4-8h2v13H7zm4-2h2v15h-2zm4 4h2v11h-2zm4-2h2v13h-2z"/>
                        </svg>
                    </div>
                    <span class="logo-text">Graph Page</span>
                </a>
                <ul class="nav-links">

                    <li><a href="#">Portfolio</a></li>

                    <li><a href="#">Reclamations</a></li>

                    <li><a href="#">Projects</a></li>

                    <!-- 🔥 THIS IS THE IMPORTANT ONE -->
                    <li>
                        <a href="index.php" class="<?php echo $view === 'list' ? 'active' : ''; ?>">
                            Formations
                        </a>
                    </li>

                    <li><a href="#">Users</a></li>

                </ul>
                <a href="https://www.google.com/search" target="_blank" rel="noopener" title="Search">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </a>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <ul class="nav-links-mobile">

                <li><a href="#">Portfolio</a></li>
                <li><a href="#">Reclamations</a></li>
                <li><a href="#">Projects</a></li>

                <li>
                    <a href="index.php" class="<?php echo $view === 'list' ? 'active' : ''; ?>">
                        Formations
                    </a>
                </li>

                <li><a href="#">Users</a></li>

            </ul>
        </nav>

        <!-- Dashboard Section -->
        <section class="dashboard-section">
        <div class="dashboard-container">

            <h2 class="section-title">📚 Formations</h2>
    <div style="display:flex; justify-content:space-between; align-items:center;">
        
        <h1>Formations</h1>

        <a href="index.php?action=add" class="btn">
            + Ajouter Formation
        </a>

    </div>
    <?php if($view == 'list'): ?>

        <?php $formations = $pdo->query("SELECT * FROM formation")->fetchAll(); ?>

        <div class="stats-grid">
            <?php foreach($formations as $f): ?>
                <div class="stat-card">

                    <div class="stat-header">
                        <div class="stat-icon">📘</div>
                        <div class="stat-title"><?= $f['titre'] ?></div>
                    </div>

                    <div class="stat-description">
                        <?= substr($f['description'], 0, 40) ?>...
                    </div>

                    <br>

                    <!-- ✅ ONLY BUTTON -->
                    <a class="cta-button" href="index.php?action=details&id=<?= $f['id'] ?>">
                        Voir plus
                    </a>

                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif($view == 'details'): ?>

        <?php
        $stmt = $pdo->prepare("SELECT * FROM formation WHERE id=?");
        $stmt->execute([$_GET['id']]);
        $f = $stmt->fetch();
        ?>

        <div class="dashboard-container">

            <h2 class="section-title">📄 Formation Details</h2>

            <div class="stat-card">

                <div class="stat-header">
                    <div class="stat-icon">📘</div>
                    <div class="stat-title"><?= $f['titre'] ?></div>
                </div>

                <div class="stat-description">
                    <?= $f['description'] ?>
                </div>

                <br><br>

                <!-- ✅ ACTIONS HERE ONLY -->
                <button class="cta-button" onclick="bookFormation()">Book</button>

                <button class="cta-button" onclick="openCertificateForm(<?= $f['id'] ?>)">
                    Get Certificate
                </button>

                <br><br>    

                <a href="index.php" class="cta-button">⬅ Retour</a>

            </div>

        </div>
    <?php elseif($view == 'showCert'): ?>

    <div id="certificate" style="
        text-align:center;
        padding:40px;
        background:white;
        color:black;
        border-radius:10px;
    ">

        <h1>🎓 Certificate</h1>

        <p>This certifies that</p>

        <h2><?= $_GET['user_name'] ?? 'No Name' ?></h2>
        <p>Date:</p>
        <strong><?= $_GET['date'] ?? '' ?></strong>

        <p>Certificate Code:</p>
        <strong><?= $_GET['code'] ?? '' ?></strong>

        <p>has successfully completed the formation</p>

        <br>

        <div class="no-print">

            <button onclick="downloadPDF()" class="cta-button">
                Download PDF
            </button>

            <br><br>

            <a href="index.php" class="cta-button">⬅ Retour</a>
            <br><br>

            <a href="index.php?action=editCert&name=<?= $_GET['user_name'] ?>&date=<?= $_GET['date'] ?>&code=<?= $_GET['code'] ?>" 
            class="cta-button">
            ✏ Modifier
            </a>

        </div>

    </div>
    <?php elseif($view == 'editCert'): ?>

<div class="dashboard-container">

    <h2>Modifier Certificat</h2>

    <form method="POST" action="index.php?action=updateCert">

        <input type="text" name="user_name" 
               value="<?= $_GET['name'] ?>" required>

        <input type="date" name="date_obtention" 
               value="<?= $_GET['date'] ?>" required>

        <input type="hidden" name="certificate_code" 
               value="<?= $_GET['code'] ?>">

        <br><br>

        <button class="cta-button">Update</button>

    </form>

</div>


    <?php elseif($view == 'add'): ?>

        <form method="POST" action="index.php?action=addAction" class="contact-form">

            <div class="form-group">
                <label>Titre</label>
                <input type="text" name="titre" id="titre" required>
                
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>

            <div class="form-group">
                <label>Domaine</label>
                <input type="text" name="domaine" required>
            </div>

            <div class="form-group">
                <label>Niveau</label>
                <select name="niveau" required>
                    <option value="">-- Choisir --</option>
                    <option value="debutant">Débutant</option>
                    <option value="intermediaire">Intermédiaire</option>
                    <option value="avance">Avancé</option>
                </select>
            </div>

            <div class="form-group">
                <label>Prix</label>
                <input type="number" step="0.01" name="prix" required>
            </div>

            <div class="form-group">
                <label>Durée</label>
                <input type="text" name="duree" required>
            </div>

            <div class="form-group">
                <label>Instructor</label>
                <input type="text" name="instructor" required>
            </div>

            <button class="cta-button">Ajouter</button>

        </form>
            <a href="index.php" class="cta-button">⬅ Retour</a>

    <?php elseif($view == 'addAction'):

        $titre = trim($_POST['titre']);

        $regex = "/^[A-Za-zÀ-ÿ\s]+$/";

        // ❌ EMPTY
        if(empty($titre)){
            die("❌ Le titre est obligatoire");
        }

        // ❌ NUMBERS NOT ALLOWED
        if(!preg_match($regex, $titre)){
            die("❌ Le titre doit contenir seulement des lettres");
        }

        // ✅ INSERT IF OK
        $stmt = $pdo->prepare("
            INSERT INTO formation 
            (titre, description, domaine, niveau, prix, duree, instructor) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_POST['titre'],
            $_POST['description'],
            $_POST['domaine'],
            $_POST['niveau'],
            $_POST['prix'],
            $_POST['duree'],
            $_POST['instructor']
        ]);

        header("Location: index.php");
        exit;

        endif;
        ?>
        <!-- Footer -->
        <footer>
            <div class="footer-content">
                <p class="copyright">© 2026 Graph Page. All rights reserved. Transforming data into insights. 
                | Designed by <a href="https://templatemo.com" rel="nofollow noopener" target="_blank">TemplateMo</a></p>
            </div>
        </footer>


<script>
document.addEventListener("DOMContentLoaded", function(){

    let titreInput = document.getElementById("titre");

    if(titreInput){

        titreInput.addEventListener("input", function(){

            // remove numbers automatically
            this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '');

        });

    }

});
</script>

    <script>
    function bookFormation(){
        let msg = document.getElementById("bookingMessage");

        msg.style.display = "block";

        // hide after 3 seconds
        setTimeout(() => {
            msg.style.display = "none";
        }, 3000);
    }
    </script>
    <script>
function downloadPDF(){

    let original = document.getElementById("certificate");

    // 🧠 clone the certificate
    let clone = original.cloneNode(true);

    // ❌ remove buttons from clone
    let buttons = clone.querySelectorAll(".no-print");
    buttons.forEach(el => el.remove());

    // 🧾 generate PDF from CLEAN clone
    html2pdf().from(clone).save("certificate.pdf");
}
</script>
<script>
function checkForm(){

    let name = document.querySelector("input[name='user_name']").value.trim();
    let date = document.querySelector("input[name='date_obtention']").value;
    let formation = document.getElementById("formationId").value;

    if(name === "" || date === "" || formation === ""){
        alert("❌ Please fill all fields!");
        return;
    }

    let regex = /^[A-Za-zÀ-ÿ\s]+$/;
    if(!regex.test(name)){
        alert("❌ Name must contain only letters!");
        return;
    }

    let today = new Date().toISOString().split("T")[0];
    if(date > today){
        alert("❌ Date cannot be in the future!");
        return;
    }

    // ✅ show quiz instead of submitting
    document.getElementById("quizBox").style.display = "block";
}

function validateQuiz(){

    let answer = document.getElementById("quizAnswer").value;

    if(answer === "correct"){

        // ✅ NOW submit form
        document.querySelector("#certificateForm form").submit();

    } else {
        alert("❌ Wrong answer!");
    }
}

function closeQuiz(){
    document.getElementById("quizBox").style.display = "none";
}
</script>
    </body>
    </html>