<?php

namespace Models;

class AuthModel extends ModeleParent
{
    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function registerUser($name, $email, $hashedPassword, $role = 'client')
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role, created_at, status) VALUES (?, ?, ?, ?, NOW(), 'active')");
        return $stmt->execute([$name, $email, $hashedPassword, $role]);
    }

    public function storePasswordResetToken($email, $token)
    {
        $stmt = $this->pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        return $stmt->execute([$email, $token]);
    }

    public function getPasswordResetToken($token)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 1 HOUR");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function deletePasswordResetToken($email)
    {
        $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        return $stmt->execute([$email]);
    }

    public function storeVerificationToken($email, $token)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET email_verified = 0, token = ? WHERE email = ?");
        return $stmt->execute([$token, $email]);
    }

    public function verifyEmail($token)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET email_verified = 1, token = NULL WHERE token = ?");
        return $stmt->execute([$token]);
    }
}
