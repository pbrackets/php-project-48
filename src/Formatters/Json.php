<?php declare(strict_types=1);

namespace Formatters\Json;

function PrintResult($array): string
{
    return PrintResultJson($array);
}
function PrintResultJson(array $data): string
{
    return json_encode($data);
}