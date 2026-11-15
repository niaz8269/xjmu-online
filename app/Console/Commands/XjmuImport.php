<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class XjmuImport extends Command
{
    protected $signature = 'xjmu:import {file=_backup/xjmu_online_database.sql : Legacy MySQL dump} {--allow-non-sqlite : Permit writing to a non-SQLite database (never use on live data)}';
    protected $description = 'Import legacy xjmu.online MySQL dump data into the Laravel database';

    public function handle(): int
    {
        $path = base_path($this->argument('file'));
        if (! is_file($path)) {
            $this->error("Dump not found: $path");
            return self::FAILURE;
        }

        // Safety: never write into a real (MySQL) database unless explicitly allowed.
        $driver = DB::connection()->getDriverName();
        if ($driver !== 'sqlite') {
            if (! $this->option('allow-non-sqlite')) {
                $this->error("Refusing to import: the connected database is '$driver', not a local SQLite copy.");
                return self::FAILURE;
            }
            if (! $this->confirm("This will WRITE into the '$driver' database '".DB::connection()->getDatabaseName()."'. Is this a staging/empty database, NOT live?", false)) {
                $this->warn('Aborted. Nothing was changed.');
                return self::FAILURE;
            }
        }
        $sql = file_get_contents($path);

        // Column lists per table come from the CREATE TABLE statements.
        preg_match_all('/CREATE TABLE `(\w+)` \((.*?)\) ENGINE/s', $sql, $m, PREG_SET_ORDER);
        $legacyCols = [];
        foreach ($m as $t) {
            preg_match_all('/^\s*`(\w+)`\s/m', $t[2], $c);
            $legacyCols[$t[1]] = $c[1];
        }

        preg_match_all('/INSERT INTO `(\w+)`(?: \(([^)]*)\))? VALUES\s*(.*?);\s*(?:\r?\n|$)/s', $sql, $ins, PREG_SET_ORDER);
        Schema::disableForeignKeyConstraints();
        $totals = [];

        foreach ($ins as $i) {
            $table = $i[1];
            $cols = $i[2] !== '' ? array_map(fn ($c) => trim($c, ' `'), explode(',', $i[2])) : ($legacyCols[$table] ?? []);
            foreach ($this->parseRows($i[3]) as $row) {
                if (count($row) !== count($cols)) { $this->warn("$table: ".count($row)." vs ".count($cols)." first=".substr(json_encode($row),0,200)); continue; } $data = array_combine($cols, $row);
                $target = $table;
                if (! Schema::hasTable($target)) { continue; }
                $existing = Schema::getColumnListing($target);
                $data = array_intersect_key($data, array_flip($existing));
                DB::table($target)->insertOrIgnore($data);
                $totals[$target] = ($totals[$target] ?? 0) + 1;
            }
        }

        foreach ($totals as $t => $n) { $this->line(str_pad($t, 22) . $n); }
        $this->info('Import complete.');
        return self::SUCCESS;
    }

    private function mapUser(array $d): array
    {
        $out = [
            'id' => $d['id'], 'name' => $d['full_name'], 'email' => $d['email'], 'password' => $d['password'],
            'role' => $d['role'] ?? 'user', 'is_superadmin' => $d['is_superadmin'] ?? 0,
            'email_verified_at' => ! empty($d['is_verified']) ? ($d['created_at'] ?? now()) : null,
            'created_at' => $d['created_at'] ?? null, 'updated_at' => $d['created_at'] ?? null,
        ];
        return $out;
    }

    /** Parse "(1,'a'),(2,NULL)" MySQL tuples (handles \' and '' escapes). */
    private function parseRows(string $s): \Generator
    {
        $len = strlen($s);
        $i = 0;
        while ($i < $len) {
            while ($i < $len && $s[$i] !== '(') { $i++; }
            if ($i >= $len) { return; }
            $i++;
            $row = [];
            while ($i < $len) {
                while ($i < $len && ($s[$i] === ' ' || $s[$i] === "\n" || $s[$i] === "\r" || $s[$i] === "\t")) { $i++; }
                $ch = $s[$i];
                if ($ch === "'") {
                    $i++;
                    $v = '';
                    while ($i < $len) {
                        if ($s[$i] === '\\') {
                            $n = $s[$i + 1] ?? '';
                            $v .= match ($n) { 'n' => "\n", 'r' => "\r", 't' => "\t", '0' => "\0", default => $n };
                            $i += 2;
                        } elseif ($s[$i] === "'") {
                            if (($s[$i + 1] ?? '') === "'") { $v .= "'"; $i += 2; } else { $i++; break; }
                        } else { $v .= $s[$i++]; }
                    }
                    $row[] = $v;
                } else {
                    $j = $i;
                    while ($j < $len && $s[$j] !== ',' && $s[$j] !== ')') { $j++; }
                    $tok = trim(substr($s, $i, $j - $i));
                    $row[] = strcasecmp($tok, 'NULL') === 0 ? null : (is_numeric($tok) ? $tok + 0 : $tok);
                    $i = $j;
                }
                while ($i < $len && ($s[$i] === ' ' || $s[$i] === "\n" || $s[$i] === "\r")) { $i++; }
                if ($s[$i] === ',') { $i++; continue; }
                if ($s[$i] === ')') { $i++; break; }
            }
            yield $row;
        }
    }
}

