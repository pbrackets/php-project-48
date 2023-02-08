<?php

namespace Formatters;

use function Formatters\Json\PrintResultJson;
use function Formatters\Stylish\PrintResultStylish;
use function Formatters\Plain\PrintResultPlain;

function format(array $tree, string $format)
{
    if ($format === 'stylish') {
        return "{\n" . PrintResultStylish($tree) . "}";
    } elseif ($format === 'plain') {
        return rtrim(PrintResultPlain($tree), '\n');
    } elseif ($format === 'json') {
        return PrintResultJson($tree);
    } else {
        echo 'Неизвестный формат: ' . $format;
    }
}
