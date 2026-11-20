<?php
namespace App\Support;
class Fmt
{
    /** 0 -> "0 mnt", 45 -> "<1 mnt", 1500 -> "25 mnt", 5400 -> "1.5 jam" */
    public static function duration(int|float|string $s): string
    {
        $s = (int) $s;
        if ($s <= 0) return '0 mnt';
        if ($s < 60) return '<1 mnt';
        if ($s < 3600) return intdiv($s, 60) . ' mnt';
        return rtrim(rtrim(number_format($s / 3600, 1, '.', ''), '0'), '.') . ' jam';
    }
}
