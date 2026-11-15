<?php
// Usage: php tools/check_files.php — reports DB-referenced files missing from public/ (local sanity check).
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$map = ['documents' => 'file_path', 'blogs' => 'featured_image', 'gallery' => 'image_path', 'downloads' => 'file_path',
    'consultants' => 'photo', 'testimonials' => 'photo', 'events' => 'featured_image'];
foreach ($map as $table => $col) {
    $total = 0;
    $missing = [];
    foreach (DB::table($table)->whereNotNull($col)->where($col, '!=', '')->pluck($col) as $path) {
        if (preg_match('~^https?://~', $path)) { continue; }
        $total++;
        if (! is_file(public_path(ltrim(rawurldecode(str_replace('\\', '/', $path)), '/')))) { $missing[] = $path; }
    }
    printf("%-13s %-15s referenced=%4d missing=%d\n", $table, $col, $total, count($missing));
    foreach (array_slice($missing, 0, 3) as $m) { echo '     e.g. ' . basename($m) . "\n"; }
}
