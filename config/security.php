<?php

return [
    // Admin session closes after this many idle minutes (0 = never).
    'admin_idle_minutes' => (int) env('ADMIN_IDLE_MINUTES', 30),
    // Sign-in lockout: this many failed attempts per email+IP within the window locks that pair.
    'login_max_attempts' => (int) env('LOGIN_MAX_ATTEMPTS', 5),
    'login_lock_minutes' => (int) env('LOGIN_LOCK_MINUTES', 15),
    // Extra guard on the IP alone (spraying many emails from one address).
    'login_ip_max_attempts' => (int) env('LOGIN_IP_MAX_ATTEMPTS', 30),
];
