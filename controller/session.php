<?php

function requireLogin(): void {
    if (empty($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }
}

function requireRole(string $role): void {
    requireLogin();
    if ($_SESSION['user']['role'] !== $role) {
        $dest = $_SESSION['user']['role'] === 'admin' ? 'dashboard' : 'client_home';
        header('Location: index.php?page=' . $dest);
        exit;
    }
}

function requireNotAdmin(): void {
    requireLogin();
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: index.php?page=dashboard');
        exit;
    }
}

function requireGuest(): void {
    if (!empty($_SESSION['user'])) {
        $dest = $_SESSION['user']['role'] === 'admin' ? 'dashboard' : 'client_home';
        header('Location: index.php?page=' . $dest);
        exit;
    }
}

function logout(): void {
    session_unset();
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

function currentUser(): array {
    return $_SESSION['user'] ?? [];
}

function updateSessionUser(array $data): void {
    foreach ($data as $key => $value) {
        $_SESSION['user'][$key] = $value;
    }
}