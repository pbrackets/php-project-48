<?php

namespace Formatters\Plain;

function formatPlainValue($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_array($value)) {
        return '[complex value]';
    }
    if (is_numeric($value)) {
        return "{$value}";
    }
    return "'{$value}'";
}

function PrintResult($array): string
{
    return PrintResultPlain($array) . "\n";
}

function PrintResultPlain(array $data, $path = ''): string
{
    $result = '';

    foreach ($data as $key => $value) {
        //$fullPath = "{$path}{$data['key']}";
        if (str_starts_with($key, '+')) {
            $newValue = formatPlainValue($value);
            $baseKey  = trim($key, "+ ");
            $fullPath = "{$path}{$baseKey}";
            $minusKey = '- ' . $baseKey;
            if (array_key_exists($minusKey, $data)) {
                $oldValue = formatPlainValue($data[$minusKey]);
                $result .= "Property '{$fullPath}' was updated. From {$oldValue} to {$newValue}\n";
            } else {
                $result .= "Property '{$fullPath}' was added with value: {$newValue}\n";
            }
        } elseif (str_starts_with($key, '-')) {
            $baseKey  = trim($key, "- ");
            $fullPath = "{$path}{$baseKey}";
            $plusKey  = '+ ' . $baseKey;
            if (!array_key_exists($plusKey, $data)) {
                $result .= "Property '{$fullPath}' was removed\n";
            }
        } else {
            if (is_array($value)) {
                $fullPath = "{$path}{$key}.";
                $result .= PrintResultPlain($value, $fullPath);
            }
        }
    }
    return rtrim($result, '\n');
}
