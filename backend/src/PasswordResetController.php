<?php
class PasswordResetController {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function requestReset($data) {
        $email = trim($data['email'] ?? '');

        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Always respond success to not reveal info.
        if (!$user) {
            json_response(['message' => 'If that email exists, a reset link has been sent.']);
        }

        $token = bin2hex(random_bytes(16));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $this->pdo->prepare("INSERT INTO password_resets (user_id, reset_token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expiresAt]);

        // Send the email with the link
        // For simplicity, we'll just pretend
        // In a real scenario, use PHPMailer and set MAILER configs
        // $resetLink = "http://localhost:3000/reset-password?token=$token";
        // sendMail($email, "Password Reset", "Click here to reset: $resetLink");

        json_response(['message' => 'If that email exists, a reset link has been sent.']);
    }

    public function resetPassword($data) {
        $token = $data['token'] ?? '';
        $newPassword = $data['new_password'] ?? '';

        if (!$token || !$newPassword) {
            json_response(['error' => 'Invalid request'], 400);
        }

        $stmt = $this->pdo->prepare("SELECT user_id, expires_at FROM password_resets WHERE reset_token = ?");
        $stmt->execute([$token]);
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reset || new DateTime() > new DateTime($reset['expires_at'])) {
            json_response(['error' => 'Invalid or expired token.'], 400);
        }

        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?")
                  ->execute([$passwordHash, $reset['user_id']]);

        $this->pdo->prepare("DELETE FROM password_resets WHERE reset_token = ?")->execute([$token]);

        json_response(['message' => 'Password reset successful!']);
    }
}
