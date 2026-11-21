<?php
// PHP di Wasmer Edge tidak menyertakan sebagian fungsi ekstensi mbstring.
// File ini menambal fungsi yang dipakai Laravel. Dimuat paling awal dari public/index.php.
if (!function_exists('mb_split')) {
    function mb_split(string $pattern, string $string, int $limit = -1): array|false
    {
        $parts = @preg_split('~' . str_replace('~', '\~', $pattern) . '~u', $string, $limit);
        return $parts === false ? false : $parts;
    }
}
