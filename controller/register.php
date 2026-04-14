<?php
require_once __DIR__ . '/../model/UserModel.php';

if (!empty($_SESSION['user'])) {
    $dest = $_SESSION['user']['role'] === 'admin' ? 'dashboard' : 'client_home';
    header('Location: index.php?page=' . $dest);
    exit;
}

$error   = '';
$success = '';

// ── helper: upload photo ──────────────────────────────────────────────────────
function handlePhotoUpload(array $file): ?string {
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] === 0) return null;
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo   = finfo_open(FILEINFO_MIME_TYPE);
    $mime    = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowed)) return null;
    if ($file['size'] > 2 * 1024 * 1024) return null; // 2 MB max
    $ext    = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name   = uniqid('avatar_', true) . '.' . strtolower($ext);
    $dest   = __DIR__ . '/../uploads/' . $name;
    if (!is_dir(__DIR__ . '/../uploads/')) mkdir(__DIR__ . '/../uploads/', 0755, true);
    return move_uploaded_file($file['tmp_name'], $dest) ? 'uploads/' . $name : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = trim($_POST['nom'] ?? '');
    $prenom    = trim($_POST['prenom'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';
    $telephone = trim($_POST['telephone'] ?? '');
    $role      = $_POST['role'] ?? 'client';

    if (!$nom || !$prenom || !$email || !$password || !$confirm) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $model = new UserModel();
        if ($model->findByEmail($email)) {
            $error = 'This email is already registered.';
        } else {
            $ok = $model->create([
                'nom'       => $nom,
                'prenom'    => $prenom,
                'email'     => $email,
                'password'  => $password,
                'role'      => in_array($role, ['client','prestataire']) ? $role : 'client',
                'telephone' => $telephone ?: null,
                'photo'     => handlePhotoUpload($_FILES['photo'] ?? ['error'=>UPLOAD_ERR_NO_FILE,'size'=>0,'tmp_name'=>'','name'=>'']),
            ]);
            if ($ok) $success = 'Account created! You can now sign in.';
            else     $error   = 'Something went wrong. Please try again.';
        }
    }
}

$html = file_get_contents(__DIR__ . '/../view/register.html');
$html = str_replace('<!--PHP_ERROR-->',   $error   ? '<div class="alert alert-danger">'  . htmlspecialchars($error)   . '</div>' : '', $html);
$html = str_replace('<!--PHP_SUCCESS-->', $success ? '<div class="alert alert-success">' . htmlspecialchars($success) . ' <a href="index.php?page=login" class="alert-link">Sign in →</a></div>' : '', $html);
$html = str_replace('value="__NOM__"',       'value="' . htmlspecialchars($_POST['nom']    ?? '') . '"', $html);
$html = str_replace('value="__PRENOM__"',    'value="' . htmlspecialchars($_POST['prenom'] ?? '') . '"', $html);
$html = str_replace('value="__EMAIL__"',     'value="' . htmlspecialchars($_POST['email']  ?? '') . '"', $html);
$html = str_replace('value="__TELEPHONE__"', 'value="' . htmlspecialchars($_POST['telephone'] ?? '') . '"', $html);
echo $html;