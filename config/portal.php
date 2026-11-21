<?php

return [
    // Emails to applicants on submission and on a status change. OFF until the wording is approved:
    // set PORTAL_NOTIFY_STUDENTS=true in .env to turn them on.
    'notify_students' => (bool) env('PORTAL_NOTIFY_STUDENTS', false),
];
