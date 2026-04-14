<?php
require_once __DIR__ . '/../model/UserModel.php';

if (!empty($_SESSION['user'])) {
    header('Location: index.php?page=dashboard');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } else {
        $model = new UserModel();
        $user  = $model->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id'        => $user['id_user'],
                'nom'       => $user['nom'],
                'prenom'    => $user['prenom'],
                'email'     => $user['email'],
                'role'      => $user['role'],
                'telephone' => $user['telephone'],
                'photo'     => $user['photo'],
            ];
            $dest = $user['role'] === 'admin' ? 'dashboard' : 'client_home';
            header('Location: index.php?page=' . $dest);
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

$errorEncoded = htmlspecialchars($error);
$emailVal     = htmlspecialchars($_POST['email'] ?? '');

// Inject error into the HTML file
$html = file_get_contents(__DIR__ . '/../view/login.html');
$html = str_replace('<!--PHP_ERROR-->', $error ? "<div class=\"alert alert-danger\">$errorEncoded</div>" : '', $html);
$html = str_replace('<!--PHP_EMAIL-->', $emailVal, $html);
echo $html;