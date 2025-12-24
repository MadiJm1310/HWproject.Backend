<?php
/* ---------------------------
   Input helpers
----------------------------*/

function sanitize(string $value): string
{
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

function isPositiveNumber($value): bool
{
    return is_numeric($value) && $value > 0;
}

/* ---------------------------
   Flash messages (sessions)
----------------------------*/

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

function getFlash(string $type): ?string
{
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

/* ---------------------------
   Redirect helper
----------------------------*/

function redirect(string $url): void
{
    header("Location: $url");
    exit;
}
