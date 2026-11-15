<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

/** Small helpers shared by the "ops" admin controllers (applications, users, blogs, events, settings...). */
trait OpsHelpers
{
    /** Query results as plain arrays (the originals used PDO::FETCH_ASSOC). */
    protected function rows(iterable $rows): array
    {
        return Collection::make($rows)->map(fn ($r) => (array) $r)->values()->all();
    }

    protected function row(?object $row): ?array
    {
        return $row === null ? null : (array) $row;
    }

    /** `(int) $_GET[$key]` with a default when absent; arrays behave like PHP's (int)array === 1. */
    protected function intQuery(Request $request, string $key, int $default = 1): int
    {
        if (! array_key_exists($key, $request->query())) {
            return $default;
        }
        $v = $request->query($key);
        return is_array($v) ? (int) ! empty($v) : (int) $v;
    }

    protected function strQuery(Request $request, string $key): string
    {
        $v = $request->query($key, '');
        return is_array($v) ? '' : (string) $v;
    }

    /** Raw submitted value as a string (the originals read $_POST without trimming/null conversion). */
    protected function post(Request $request, string $key, string $default = ''): string
    {
        $v = $request->input($key, $default);
        return is_array($v) ? '' : (string) ($v ?? $default);
    }

    /** What the originals did with die("text"): a plain 200 response. */
    protected function plain(string $text, int $status = 200): Response
    {
        return response($text, $status);
    }

    /** One-shot session value, like `isset($_SESSION[k]) ... unset($_SESSION[k])` in the original views. */
    protected function pull(string $key): ?string
    {
        if (! session()->has($key)) {
            return null;
        }
        $v = session()->pull($key);
        return (string) $v;
    }
}
