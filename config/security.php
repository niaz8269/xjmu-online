<?php

return [
    // Admin session closes after this many idle minutes (0 = never).
    'admin_idle_minutes' => (int) env('ADMIN_IDLE_MINUTES', 30),
    // Sign-in lockout: this many failed attempts per email+IP within the window locks that pair.
    'login_max_attempts' => (int) env('LOGIN_MAX_ATTEMPTS', 5),
    'login_lock_minutes' => (int) env('LOGIN_LOCK_MINUTES', 15),
    // Extra guard on the IP alone (spraying many emails from one address).
    'login_ip_max_attempts' => (int) env('LOGIN_IP_MAX_ATTEMPTS', 30),
    // Two-step sign-in for admins: after the password, a 6-digit code is emailed and must be entered.
    // OFF until switched on with ADMIN_TWO_STEP=true (test that the code email arrives first).
    'admin_two_step' => (bool) env('ADMIN_TWO_STEP', false),
    'admin_code_minutes' => 10,
    'admin_code_max_tries' => 5,
];
