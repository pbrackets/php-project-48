<?php

namespace Formatters\Stylish;

function PrintResult($array): string
{
    return "{\n" . PrintResultStylish($array) . "}\n";
}

function PrintResultStylish($data, $level = 0): string
{
    $result = '';
    $prefix = str_repeat(" ", 4 * ($level + 1) - 2);
    foreach ($data as $key => $value) {
        if (substr($key, 0, 1) !== '-' && substr($key, 0, 1) !== '+') {
            $key = '  ' . $key;
        }

        if (is_bool($value) && $value === true) {
            $result .= "{$prefix}{$key}: true\n";
        } elseif (is_bool($value) && $value === false) {
            $result .= "{$prefix}{$key}: false\n";
        } elseif (is_null($value) && $value === null) {
            $result .= "{$prefix}{$key}: null\n";
        } elseif (!is_array($value)) {
            $result .= "{$prefix}{$key}: $value\n";
        } elseif (is_array($value)) {
            $data = PrintResultStylish($value, $level + 1);
            $result .= "{$prefix}{$key}: {\n" . trim($data, "\n") . "\n{$prefix}  }\n";
        }
    }

    return $result;
}
