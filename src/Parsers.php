<?php

namespace Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, string $type)
{
    switch ($type) {
        case 'json':
            return json_decode($data, true);
        case 'yml':
        case 'yaml':
            return Yaml::parse($data);
            return Yaml::parse($data);
        default:
            echo ("Неизвестное расширение");
    }
}
