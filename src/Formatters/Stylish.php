<?php

namespace Formatters\Stylish;

use function Differ\Differ\getKey;
use function Differ\Differ\getValue1;
use function Differ\Differ\getValue2;
use function Differ\Differ\getType;
use function Differ\Differ\getChildren;

function wrapStrings(array $tree, string $prefix): string
{
    return "{\n" . implode("\n", $tree) . "\n" . $prefix . "}";
}
function toString($node, int $level): string
{
    if (!is_array($node)) {
        return is_null($node) ? "null" : trim(var_export($node, true), "'");
    }

    $prefix = str_repeat(" ", 4 * ($level + 1) - 2);

    $tree = array_map(function ($key, $value) use ($prefix, $level) {
        $stringValue = toString($value, $level + 1);
        return "{$prefix}    {$key}: {$stringValue}";
    }, array_keys($node), $node);

    return wrapStrings($tree, $prefix);
}

function PrintResultStylish($data, int $level = 0): string
{
    $prefix = str_repeat(" ", 2 * ($level + 1) - 2);

    $callback = function ($acc, $node) use ($prefix, $level) {
        $key  = getKey($node);
        $type = getType($node);

        if ($type === 'nested') {
            $children = PrintResultStylish(getChildren($node), $level + 1);

            return [...$acc, "{$prefix}    {$key}: {$children}"];
        }

        $value1 = toString(getValue1($node), $level + 1);
        $value2 = toString(getValue2($node), $level + 1);
        if ($type === 'added') {
            return [...$acc, "{$prefix}  + {$key}: {$value1}"];
        }
        if ($type === 'removed') {
            return [...$acc, "{$prefix}  - {$key}: {$value1}"];
        }

        if ($type === 'updated') {
            return [
                ...$acc,
                "{$prefix}  - {$key}: {$value1}",
                "{$prefix}  + {$key}: {$value2}"
            ];
        }
        return [...$acc, "{$prefix}    {$key}: {$value1}"];
    };

    $tree = array_reduce($data, $callback, []);
    return wrapStrings($tree, $prefix);
}
