<?php

namespace Formatters\Plain;

use function Differ\Differ\getChildren;
use function Differ\Differ\getKey;
use function Differ\Differ\getValue1;
use function Differ\Differ\getValue2;
use function Differ\Differ\getType;

function formatPlainValue(mixed $value): string
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

function PrintResultPlain(array $data, string $path = ''): string
{
    $callback = function ($node) use ($path) {
        $key  = getKey($node);
        $type = getType($node);
        $fullPath = "{$path}{$key}";
        if ($type === 'nested') {
            $fullPath2 = "{$path}{$key}.";
            return PrintResultPlain(getChildren($node), $fullPath2);
        }
        if ($type === 'added') {
            $value1 =  formatPlainValue(getValue1($node));
            return "Property '{$fullPath}' was added with value: {$value1}\n";
        }
        if ($type === 'removed') {
            return "Property '{$fullPath}' was removed\n";
        }
        if ($type === 'updated') {
            $value1 = formatPlainValue(getValue1($node));
            $value2 = formatPlainValue(getValue2($node));
            return "Property '{$fullPath}' was updated. From {$value1} to {$value2}\n";
        }
        if ($type === 'unchanged') {
            return '';
        }
    };
    $result = array_map($callback, $data);
    return implode('', $result);
}
