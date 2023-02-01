<?php

namespace Formatters\Plain;

function PrintResult($array): string
{
    return PrintResultPlain($array);
}

function PrintResultPlain($data, $level = 0, $path = ''): string
{
    foreach ($data as $key => $value) {
        $result = '';


        if (str_starts_with($key, '+')) {
            $newValue = $value;
            $newKeyPlus = trim($key, "+ ");
            if (!is_array($value)) {
                $result .= "Property '{$newKeyPlus}' was added with value: {$newValue}";
            } else {
                $result .= "Property '{$newKeyPlus}' was added with value: [complex value]";
            }
        } elseif (str_starts_with($key, '-')) {
            $oldValue = $value;
            $newKeyMinus = trim($key, "- ");
            if (!is_array($value)) {
                $result .= "Property '{$newKeyPlus}' was removed";
            } else {
                $result .= "Property '{$newKeyPlus}' was updated. From [complex value] to $newKeyMinus";
            }
        } elseif ($newKeyPlus === $newKeyMinus) {
            $result .= "Property '{$newKeyMinus}' was updated. From {$oldValue} to {$newValue}";
    }
    return $result;
}

// if (is_bool($value) && $value === true) {
//     $value = true;
// } elseif (is_bool($value) && $value === false) {
//     $value = false;
// } elseif (is_null($value) && $value === null) {
//     $value = null;
// }

