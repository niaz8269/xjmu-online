<?php

use App\Support\Settings;

if (! function_exists('s')) {
    function s(string $key, ?string $default = ''): ?string { return Settings::get($key, $default); }
}
if (! function_exists('img')) {
    function img(?string $path): string { return Settings::asset($path); }
}
