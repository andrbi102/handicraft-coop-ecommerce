<?php

declare(strict_types=1);

namespace App\Core;

use Database;
use Crypto;
use SecureSession;

/**
 * احراز هویت — کاربر جاری، نقش‌ها، ورود/خروج.
 *
 * رمزها همیشه با Argon2id (scaffolds/crypto.php) هش می‌شوند؛ نشست پس از
 * ورود regenerate می‌شود (جلوگیری از Session Fixation).
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level security
 */
final class Auth
{
    private ?array $cachedUser = null;

    public function __construct(private Database $db)
    {
    }

    /** کاربر جاری یا null. */
    public function user(): ?array
    {
        if ($this->cachedUser !== null) {
            return $this->cachedUser;
        }
        $id = $_SESSION['user_id'] ?? null;
        if (!is_int($id) && !ctype_digit((string) $id)) {
            return null;
        }
        $rows = $this->db->query(
            'SELECT id, name, email, phone, role, status FROM users WHERE id = ? AND status = ?',
            [(int) $id, 'active'],
        );
        $this->cachedUser = $rows[0] ?? null;

        return $this->cachedUser;
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function id(): ?int
    {
        $u = $this->user();

        return $u === null ? null : (int) $u['id'];
    }

    public function role(): ?string
    {
        $u = $this->user();

        return $u === null ? null : (string) $u['role'];
    }

    public function isStaff(): bool
    {
        return in_array($this->role(), ['manager', 'accountant', 'admin'], true);
    }

    public function isAdmin(): bool
    {
        return $this->role() === 'admin';
    }

    /**
     * تلاش ورود — موفقیت یعنی نشست برقرار شده.
     */
    public function attempt(string $email, string $password): bool
    {
        $rows = $this->db->query(
            'SELECT id, name, password_hash, role, status FROM users WHERE email = ? LIMIT 1',
            [$email],
        );
        $user = $rows[0] ?? null;
        if ($user === null) {
            return false; // کاربر وجود ندارد — پیام یکسان در کنترلر
        }
        if ((string) $user['status'] !== 'active') {
            return false;
        }
        if (!Crypto::passwordVerify($password, (string) $user['password_hash'])) {
            return false;
        }

        // مهاجرت تدریجی به Argon2id اگر هش قدیمی باشد (password_needs_rehash).
        if (password_needs_rehash((string) $user['password_hash'], PASSWORD_ARGON2ID)) {
            $this->db->execute(
                'UPDATE users SET password_hash = ? WHERE id = ?',
                [Crypto::passwordHash($password), (int) $user['id']],
            );
        }

        SecureSession::regenerate();
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_role'] = (string) $user['role'];
        $this->cachedUser = null;

        return true;
    }

    public function loginById(int $id): void
    {
        SecureSession::regenerate();
        $_SESSION['user_id'] = $id;
        $this->cachedUser = null;
    }

    public function logout(): void
    {
        SecureSession::destroy();
    }
}
