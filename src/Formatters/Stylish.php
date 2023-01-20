<?php

namespace Formatters\Stylish;

function PrintResult($data, $level = 0): string
{
    $result = '';
    $prefix = str_repeat("    ", $level);
    foreach ($data as $key => $value) {
        if (is_bool($value) && $value === true) {
            $result .= "{$prefix}{$key}: true\n";
        } elseif (is_bool($value) && $value === false) {
            $result .= "{$prefix}{$key}: false\n";
        } elseif (is_null($value) && $value === null) {
            $result .= "{$prefix}{$key}: null\n";
        } elseif (!is_array($value)) {
            $result .= "{$prefix}{$key}: $value\n";
        } elseif (is_array($value)) {
            $data = PrintResult($value, $level + 1);

            $result .= "{$prefix}{$key}: {\n" . trim($data, "\n") . "\n{$prefix}}\n";
        }
    }
    return $result;
}