<?php

namespace Differ\Differ;

use function Parsers\parse;
use function Formatters\format;

function makeLeaf(string $key, string $type, mixed $value1, mixed $value2 = null): array
{
    return ['key' => $key, 'type' => $type, 'value1' => $value1, 'value2' => $value2];
}

function makeNode(string $key, string $type, array $children): array
{
    return ['key' => $key, 'type' => $type, 'children' => $children];
}

function getKey(array $node): string
{
    return $node['key'];
}
function getValue1(array $node): mixed
{
    return $node['value1'];
}

function getValue2(array $node): mixed
{
    return $node['value2'];
}
function getType(array $node): string
{
    return $node['type'];
}

function getChildren(array $node): array
{
    return $node['children'];
}

function buildTree(array $firstArray, array $secondArray): array
{
    $keys1 = array_keys($firstArray);
    $keys2 = array_keys($secondArray);
    $keys = array_unique(array_merge($keys1, $keys2));


    $callback = function ($key) use ($firstArray, $secondArray) {
        $value1 = $firstArray[$key] ?? null;
        $value2 = $secondArray[$key] ?? null;

        if (!array_key_exists($key, $firstArray)) {
            return makeLeaf($key, 'added', $value2);
        }
        if (!array_key_exists($key, $secondArray)) {
            return makeLeaf($key, 'removed', $value1);
        }
        if ($value1 === $value2) {
            return makeLeaf($key, 'unchanged', $value1);
        }
        if (!is_array($value1) || !is_array($value2)) {
            return makeLeaf($key, 'updated', $value1, $value2);
        }

        $result = buildTree($value1, $value2);

        return makeNode($key, 'nested', $result);
    };

    sort($keys);
    return array_map($callback, $keys);
}

function genDiff(string $firstFileName, string $secondFileName, string $format = 'stylish'): string
{
    if (!file_exists($firstFileName) || !file_exists($secondFileName)) {
        echo "Неверные пути до файлов\n";
    }
//прочитать содержимое файлов в переменные
    $firstFileContent  = file_get_contents($firstFileName);
    $secondFileContent = file_get_contents($secondFileName);
    $firstFileExtension = pathinfo($firstFileName, PATHINFO_EXTENSION);
    $secondFileExtension = pathinfo($secondFileName, PATHINFO_EXTENSION);

//преобразовать содержимое файлов в массив и присвоить в переменные

    $firstArray  = parse($firstFileContent, $firstFileExtension);
    $secondArray = parse($secondFileContent, $secondFileExtension);

    $tree = buildTree($firstArray, $secondArray);
    return format($tree, $format);
}
