<?php

namespace App\Support;

/** CSV cell quoting that is safe to open in Excel. */
class Csv
{
    /** Quote a value; a leading = + - @ (or tab / CR) would run as a formula in Excel, so it is neutralised. */
    public static function cell($value): string
    {
        $value = (string) $value;
        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@', chr(9), chr(13)], true)) {
            $value = "'" . $value;
        }

        return '"' . str_replace('"', '""', $value) . '"';
    }
}
