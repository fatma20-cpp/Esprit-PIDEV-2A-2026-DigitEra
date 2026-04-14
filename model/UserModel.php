<?php
require_once __DIR__ . '/Database.php';

class UserModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id_user = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (nom, prenom, email, password, role, telephone, photo)
             VALUES (:nom, :prenom, :email, :password, :role, :telephone, :photo)'
        );
        return $stmt->execute([
            ':nom'       => $data['nom'],
            ':prenom'    => $data['prenom'],
            ':email'     => $data['email'],
            ':password'  => password_hash($data['password'], PASSWORD_BCRYPT),
            ':role'      => $data['role'] ?? 'client',
            ':telephone' => $data['telephone'] ?? null,
            ':photo'     => $data['photo'] ?? null,
        ]);
    }

    public function updateProfile(int $id, array $data): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE users SET nom=:nom, prenom=:prenom, email=:email, telephone=:telephone WHERE id_user=:id'
        );
        return $stmt->execute([
            ':nom'       => $data['nom'],
            ':prenom'    => $data['prenom'],
            ':email'     => $data['email'],
            ':telephone' => $data['telephone'] ?? null,
            ':id'        => $id,
        ]);
    }

    public function updatePhoto(int $id, string $photo): bool {
        $stmt = $this->pdo->prepare('UPDATE users SET photo=:photo WHERE id_user=:id');
        return $stmt->execute([':photo' => $photo, ':id' => $id]);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        $stmt = $this->pdo->prepare('UPDATE users SET password=:password WHERE id_user=:id');
        return $stmt->execute([
            ':password' => password_hash($newPassword, PASSWORD_BCRYPT),
            ':id'       => $id,
        ]);
    }

    public function getAllUsers(): array {
        $stmt = $this->pdo->query(
            'SELECT id_user, nom, prenom, email, role, telephone, date_inscription
             FROM users ORDER BY date_inscription DESC'
        );
        return $stmt->fetchAll();
    }

    public function deleteUser(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id_user = ?');
        return $stmt->execute([$id]);
    }
}