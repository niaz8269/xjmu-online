<?php
/**
 * Shared security helpers (added during the backend hardening pass).
 * Safe to include multiple times.
 */

if (!function_exists('mail_safe')) {
    /**
     * Strip CR/LF/NUL so a value can be safely placed in a mail header or
     * Subject line (prevents SMTP/mail() header injection). Audit #9.
     */
    function mail_safe($value) {
        return trim(str_replace(["\r", "\n", "\0", "%0a", "%0d", "%0A", "%0D"], ' ', (string) $value));
    }
}

if (!function_exists('valid_email')) {
    function valid_email($email) {
        $email = trim((string) $email);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
    }
}

if (!function_exists('e')) {
    /** HTML-escape for output. Audit #12/#21/#32. */
    function e($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

/* ---------------------------------------------------------------------------
 * CSRF protection (audit #19/#20). Token is per-session; verify on every
 * state-changing POST. Requires a session to already be started.
 * ------------------------------------------------------------------------- */

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /** Hidden input for HTML forms. */
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('csrf_check')) {
    /**
     * Validate a submitted token (from POST field or X-CSRF-Token header).
     * Returns true/false; callers decide how to respond.
     */
    function csrf_check($token = null) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        if ($token === null) {
            $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        }
        return !empty($_SESSION['csrf_token']) && is_string($token)
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('json_api_guard')) {
    /**
     * Install an exception/fatal handler that returns a JSON error envelope
     * instead of a blank 500 when a DB/PDO (or any) exception escapes (audit #13).
     * Call once at the very top of a JSON endpoint.
     */
    function json_api_guard() {
        set_exception_handler(function ($e) {
            error_log('Unhandled exception in JSON endpoint: ' . $e->getMessage());
            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: application/json');
            }
            echo json_encode([
                'status'  => 'error',
                'success' => false,
                'message' => 'A server error occurred. Please try again later.',
            ]);
        });
        register_shutdown_function(function () {
            $err = error_get_last();
            if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
                error_log('Fatal in JSON endpoint: ' . $err['message']);
                if (!headers_sent()) {
                    http_response_code(500);
                    header('Content-Type: application/json');
                }
                echo json_encode([
                    'status'  => 'error',
                    'success' => false,
                    'message' => 'A server error occurred. Please try again later.',
                ]);
            }
        });
    }
}

if (!function_exists('fatal_guard')) {
    /**
     * Non-JSON counterpart to json_api_guard(): logs the real exception/fatal
     * error and shows a friendly message + 500 instead of a blank fatal-error
     * page. Use on file-download / non-API endpoints (e.g. download_application.php)
     * where a JSON envelope would be wrong.
     */
    function fatal_guard($logFile, $friendlyMessage) {
        $log = function ($msg) use ($logFile) {
            $dir = dirname($logFile);
            if (!is_dir($dir)) @mkdir($dir, 0755, true);
            @error_log('[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n", 3, $logFile);
        };
        set_exception_handler(function ($e) use ($log, $friendlyMessage) {
            $log('Unhandled exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            if (!headers_sent()) http_response_code(500);
            echo $friendlyMessage;
        });
        register_shutdown_function(function () use ($log, $friendlyMessage) {
            $err = error_get_last();
            if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
                $log('Fatal error: ' . $err['message'] . ' in ' . $err['file'] . ':' . $err['line']);
                if (!headers_sent()) http_response_code(500);
                echo $friendlyMessage;
            }
        });
    }
}

if (!function_exists('csrf_require')) {
    /**
     * Enforce CSRF on a POST request. On failure sends 403 and ends the
     * request — as JSON if $asJson, else plain text.
     */
    function csrf_require($asJson = false) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        if (!csrf_check()) {
            http_response_code(403);
            if ($asJson) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Invalid or missing security token. Please refresh and try again.']);
            } else {
                echo 'Invalid or missing security token. Please refresh the page and try again.';
            }
            exit;
        }
    }
}
