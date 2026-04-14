<?php
require_once __DIR__ . '/../model/UserModel.php';

if (empty($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

$currentUser = $_SESSION['user'];
$model       = new UserModel();
$error       = '';
$success     = '';

// ── helper: upload photo ──────────────────────────────────────────────────────
function handlePhotoUpload(array $file): ?string {
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] === 0) return null;
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo   = finfo_open(FILEINFO_MIME_TYPE);
    $mime    = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowed)) return null;
    if ($file['size'] > 2 * 1024 * 1024) return null;
    $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid('avatar_', true) . '.' . strtolower($ext);
    $dest = __DIR__ . '/../uploads/' . $name;
    if (!is_dir(__DIR__ . '/../uploads/')) mkdir(__DIR__ . '/../uploads/', 0755, true);
    return move_uploaded_file($file['tmp_name'], $dest) ? 'uploads/' . $name : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'profile';

    if ($action === 'profile') {
        $nom       = trim($_POST['nom'] ?? '');
        $prenom    = trim($_POST['prenom'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');

        if (!$nom || !$prenom || !$email) {
            $error = 'Name and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $existing = $model->findByEmail($email);
            if ($existing && $existing['id_user'] != $currentUser['id']) {
                $error = 'This email is already used by another account.';
            } else {
                $ok = $model->updateProfile($currentUser['id'], [
                    'nom'       => $nom,
                    'prenom'    => $prenom,
                    'email'     => $email,
                    'telephone' => $telephone ?: null,
                ]);
                if ($ok) {
                    $_SESSION['user']['nom']       = $nom;
                    $_SESSION['user']['prenom']    = $prenom;
                    $_SESSION['user']['email']     = $email;
                    $_SESSION['user']['telephone'] = $telephone;
                    $currentUser = $_SESSION['user'];
                    $success = 'Profile updated successfully!';
                } else {
                    $error = 'Update failed. Please try again.';
                }
            }
        }
    }

    if ($action === 'password') {
        $current = $_POST['current_password'] ?? '';
        $newPw   = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $dbUser = $model->findById($currentUser['id']);
        if (!$current || !$newPw || !$confirm) {
            $error = 'Please fill in all password fields.';
        } elseif (!password_verify($current, $dbUser['password'])) {
            $error = 'Current password is incorrect.';
        } elseif (strlen($newPw) < 6) {
            $error = 'New password must be at least 6 characters.';
        } elseif ($newPw !== $confirm) {
            $error = 'New passwords do not match.';
        } else {
            $ok = $model->updatePassword($currentUser['id'], $newPw);
            if ($ok) $success = 'Password changed successfully!';
            else     $error   = 'Password update failed. Please try again.';
        }
    }

    if ($action === 'photo') {
        $newPhoto = handlePhotoUpload($_FILES['photo'] ?? [
            'error'    => UPLOAD_ERR_NO_FILE,
            'size'     => 0,
            'tmp_name' => '',
            'name'     => '',
        ]);
        if (!$newPhoto) {
            $error = 'Invalid file. Please upload a JPG, PNG or WEBP image under 2 MB.';
        } else {
            // Delete old photo file if it exists
            if (!empty($currentUser['photo']) && file_exists(__DIR__ . '/../' . $currentUser['photo'])) {
                @unlink(__DIR__ . '/../' . $currentUser['photo']);
            }
            $ok = $model->updatePhoto($currentUser['id'], $newPhoto);
            if ($ok) {
                $_SESSION['user']['photo'] = $newPhoto;
                $currentUser = $_SESSION['user'];
                $success = 'Profile photo updated!';
            } else {
                $error = 'Photo update failed. Please try again.';
            }
        }
    }
}

// ── Build template variables ──────────────────────────────────────────────────
$initials  = strtoupper(substr($currentUser['prenom'], 0, 1) . substr($currentUser['nom'], 0, 1));
$fullName  = htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom']);
$photoPath = !empty($currentUser['photo']) ? htmlspecialchars($currentUser['photo']) : '';

// Avatar shown in sidebar circle (photo or initials)
$avatarHtml = $photoPath
    ? '<img src="' . $photoPath . '" alt="Profile photo">'
    : $initials;

// Avatar shown in top nav
$navAvatarHtml = $photoPath
    ? '<img src="' . $photoPath . '" alt="Profile photo" style="width:32px;height:32px;border-radius:6px;object-fit:cover;">'
    : $initials;

// Role badge colours
$roleBadge = [
    'admin'       => ['color' => '#38BDF8', 'bg' => 'rgba(56,189,248,.12)'],
    'client'      => ['color' => '#22C55E', 'bg' => 'rgba(34,197,94,.12)'],
    'prestataire' => ['color' => '#F59E0B', 'bg' => 'rgba(245,158,11,.12)'],
];
$badge = $roleBadge[$currentUser['role']] ?? $roleBadge['client'];

// ── Render ────────────────────────────────────────────────────────────────────
$html = file_get_contents(__DIR__ . '/../view/profile.html');

$html = str_replace('__FULLNAME__',    $fullName,      $html);
$html = str_replace('__INITIALS__',    $initials,      $html);
$html = str_replace('__AVATAR__',      $avatarHtml,    $html);
$html = str_replace('__NAV_AVATAR__',  $navAvatarHtml, $html);
$html = str_replace('__ROLE__',        ucfirst(htmlspecialchars($currentUser['role'])), $html);
$html = str_replace('__ROLE_COLOR__',  $badge['color'], $html);
$html = str_replace('__ROLE_BG__',     $badge['bg'],    $html);
$html = str_replace('__NOM__',         htmlspecialchars($currentUser['nom']),            $html);
$html = str_replace('__PRENOM__',      htmlspecialchars($currentUser['prenom']),         $html);
$html = str_replace('__EMAIL__',       htmlspecialchars($currentUser['email']),          $html);
$html = str_replace('__TELEPHONE__',   htmlspecialchars($currentUser['telephone'] ?? ''), $html);

$html = str_replace('<!--PHP_ERROR-->',
    $error   ? '<div class="alert alert-danger">'  . htmlspecialchars($error)   . '</div>' : '',
    $html);
$html = str_replace('<!--PHP_SUCCESS-->',
    $success ? '<div class="alert alert-success">' . htmlspecialchars($success) . '</div>' : '',
    $html);

echo $html;