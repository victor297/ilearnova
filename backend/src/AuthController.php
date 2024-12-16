<?php
class AuthController {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function signup($data) {
        $firstName = trim($data['first_name'] ?? '');
        $lastName = trim($data['last_name'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $role = trim($data['role'] ?? 'user');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (!$firstName || !$lastName || !$email || !$password) {
            json_response(['error' => 'Missing required fields'], 400);
        }

        
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            json_response(['error' => 'Email already in use.'], 400);
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (first_name, last_name, phone, role, email, password_hash)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$firstName, $lastName, $phone, $role, $email, $passwordHash]);

        $userId = $this->pdo->lastInsertId();

        start_session_if_not_started();
        $_SESSION['user_id'] = $userId;

        json_response(['message' => 'Signup successful!', 'user_id' => $userId]);
    }

    public function login($data) {
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        $stmt = $this->pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            json_response(['error' => 'Invalid credentials.'], 401);
        }

        start_session_if_not_started();
        $_SESSION['user_id'] = $user['id'];

        json_response(['message' => 'Login successful', 'user_id' => $user['id']]);
    }

    public function logout() {
        start_session_if_not_started();
        session_destroy();
        json_response(['message' => 'Logged out successfully']);
    }

    public function me() {
        start_session_if_not_started();
        if (!isset($_SESSION['user_id'])) {
            json_response(['error' => 'Not authenticated'], 401);
        }

        $stmt = $this->pdo->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        json_response(['user' => $user]);
    }
}
