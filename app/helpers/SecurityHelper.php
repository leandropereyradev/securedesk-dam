<?php

namespace app\helpers;

class SecurityHelper
{

  private static function generateCsrfToken(): string
  {
    if (!isset($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
  }

  public static function csrfField(): string
  {
    $token = self::generateCsrfToken();

    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
  }

  public static function verifyCsrf(): void
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      return;
    }

    $sessionToken = $_SESSION['csrf_token'] ?? null;
    $formToken = $_POST['csrf_token'] ?? null;

    if (!$sessionToken || !$formToken || !hash_equals($sessionToken, $formToken)) {
      http_response_code(403);
      die("CSRF token inválido.");
    }

    unset($_SESSION['csrf_token']);
  }

  public static function escapeXSS(?string $value): string
  {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  }
}
