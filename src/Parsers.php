<?php

namespace Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(mixed $data, string $type): array
{
    switch ($type) {
        case 'json':
            return json_decode($data, true);
        case 'yml':
        case 'yaml':
            return Yaml::parse($data);
        default:
            die("Неизвестное расширение: " . $type);
    }
}
