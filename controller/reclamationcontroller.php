<?php
session_start();
require_once('../model/Reclamation.php');
require_once('../config/database.php');

class ReclamationController {

    // 🔹 Ajouter une réclamation
public function ajouter() {
    if (isset($_POST['ajouter'])) {

        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $sujet = trim($_POST['sujet']);
        $type = $_POST['type_probleme'];
        $message = trim($_POST['message']);

        $errors = [];

        // 🔹 Champs obligatoires
        if ($nom == "" || $prenom == "" || $email == "" || $sujet == "" || $message == "") {
            $errors[] = "Tous les champs sont obligatoires";
        }

        // 🔹 Nom et prénom max 20
        if (strlen($nom) > 20) {
            $errors[] = "Nom max 20 caractères";
        }

        if (strlen($prenom) > 20) {
            $errors[] = "Prénom max 20 caractères";
        }

        // 🔹 Nom et prénom: seulement des lettres
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nom)) {
            $errors[] = "Nom: seulement des lettres";
        }

        if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $prenom)) {
            $errors[] = "Prénom: seulement des lettres";
        }

        // 🔹 Email format prenom.nom@domaine.com
        if (!preg_match("/^[a-zA-Z]+\.[a-zA-Z]+@[a-zA-Z]+\.[a-zA-Z]+$/", $email)) {
            $errors[] = "Email invalide (ex: prenom.nom@domaine.com)";
        }

        // 🔹 Sujet max 50
        if (strlen($sujet) > 50) {
            $errors[] = "Sujet max 50 caractères";
        }

        // 🔹 Message max 200
        if (strlen($message) > 200) {
            $errors[] = "Message max 200 caractères";
        }

        // ❌ Si erreurs
        if (!empty($errors)) {
            foreach ($errors as $e) {
                echo "<p style='color:red;'>$e</p>";
            }
            return;
        }

        // ✅ Insertion
        $reclamation = new Reclamation($GLOBALS['conn']);
        $reclamation->nom = $nom;
        $reclamation->prenom = $prenom;
        $reclamation->email = $email;
        $reclamation->sujet = $sujet;
        $reclamation->type_probleme = $type;
        $reclamation->message = $message;

        // 🔹 Générer client_id s'il n'existe pas
        if (!isset($_SESSION['client_id'])) {
            $_SESSION['client_id'] = $reclamation->generateClientId();
        }
        $reclamation->id_client = $_SESSION['client_id'];

        if ($reclamation->create()) {
            // 🔹 Stocker l'email du client en session pour filtrer les réclamations
            $_SESSION['client_email'] = $email;
            $_SESSION['success_message'] = "✅ Réclamation envoyée avec succès! Votre numéro: " . $reclamation->id_reclamation;
            $_SESSION['reclamation_id'] = $reclamation->id_reclamation;
            
            // 🔹 Redirection vers Mes Réclamations après succès
            header("Location: ../view/front/mes_reclamations.php");
            exit;
        } else {
            echo "<p style='color:red;'>❌ Erreur lors de l'enregistrement</p>";
        }
    }
}

    // 🔹 Afficher liste
    public function listReclamations() {
        $model = new Reclamation($GLOBALS['conn']);
        return $model->read();
    }

    // 🔹 Répondre à une réclamation (Backend)
    public function repondre() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repondre'])) {
            $id = $_POST['id'] ?? null;
            $reponse = trim($_POST['reponse'] ?? '');

            if (!$id || $reponse === '') {
                $_SESSION['error_message'] = "❌ La réponse ne peut pas être vide";
                header("Location: ../view/back/consulter.php?id=$id");
                exit;
            }

            $model = new Reclamation($GLOBALS['conn']);
            if ($model->saveResponse($id, $reponse)) {
                $_SESSION['success_message'] = "✅ Réponse envoyée avec succès!";
                header("Location: ../view/back/liste_reclamation.php");
            } else {
                $_SESSION['error_message'] = "❌ Erreur lors de l'envoi de la réponse";
                header("Location: ../view/back/consulter.php?id=$id");
            }
            exit;
        }
    }
}
    public function delete($id) {
        $clientId = $_SESSION['client_id'] ?? null;
        
        // 🔹 Vérifier que le client est identifié
        if (!$clientId) {
            $_SESSION['error_message'] = "❌ Accès non autorisé";
            header("Location: ../view/front/mes_reclamations.php");
            exit;
        }
        
        $model = new Reclamation($GLOBALS['conn']);
        
        // 🔹 Vérifier que la réclamation appartient à ce client
        $reclamation = $model->getByIdAndClientId($id, $clientId);
        if (!$reclamation) {
            $_SESSION['error_message'] = "❌ Vous n'avez pas le droit de supprimer cette réclamation";
            header("Location: ../view/front/mes_reclamations.php");
            exit;
        }
        
        if ($model->delete($id)) {
            $_SESSION['success_message'] = "✅ Réclamation supprimée avec succès!";
            header("Location: ../view/front/mes_reclamations.php");
        } else {
            $_SESSION['error_message'] = "❌ Erreur lors de la suppression";
            header("Location: ../view/front/mes_reclamations.php");
        }
        exit;
    }
    // 🔹 Modifier une réclamation
    public function update() {
        if (isset($_POST['update'])) {

            $sujet = trim($_POST['sujet']);
            $type_probleme = $_POST['type_probleme'];
            $message = trim($_POST['message']);

            $errors = [];

            // 🔹 Champs obligatoires
            if ($sujet == "" || $message == "") {
                $errors[] = "Tous les champs sont obligatoires";
            }

            // 🔹 Sujet max 50
            if (strlen($sujet) > 50) {
                $errors[] = "Sujet max 50 caractères";
            }

            // 🔹 Message max 200
            if (strlen($message) > 200) {
                $errors[] = "Message max 200 caractères";
            }

            // ❌ Si erreurs
            if (!empty($errors)) {
                foreach ($errors as $e) {
                    echo "<p style='color:red;'>$e</p>";
                }
                return;
            }

            $reclamation = new Reclamation($GLOBALS['conn']);

            $reclamation->id_reclamation = $_POST['id'];
            $reclamation->sujet = $sujet;
            $reclamation->type_probleme = $type_probleme;
            $reclamation->message = $message;

            if ($reclamation->update()) {
                $_SESSION['success_message'] = "✅ Réclamation modifiée avec succès!";
                header("Location: ../view/front/mes_reclamations.php");
                exit;
            } else {
                echo "❌ Erreur lors de la modification";
            }
        }
    }
}

// ✅ Instancier et exécuter le contrôleur
$database = new Database();
$conn = $database->connect();
$GLOBALS['conn'] = $conn;

$controller = new ReclamationController();

// Traiter le formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $controller->ajouter();
}

// Traiter la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $controller->update();
}

// Traiter la réponse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repondre'])) {
    $controller->repondre();
}

// Traiter les actions GET (delete, consulter, repondre)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? null;
    
    if ($action === 'delete' && $id) {
        $controller->delete($id);
    }
    
    if ($action === 'consulter' && $id) {
        // 🔹 Afficher la page de consultation avec interface de réponse
        header("Location: /service_client/view/back/consulter.php?id=$id");
        exit;
    }
    
    if ($action === 'repondre' && $id) {
        // 🔹 Afficher l'interface de réponse
        header("Location: /service_client/view/back/consulter.php?id=$id&mode=repondre");
        exit;
    }
}