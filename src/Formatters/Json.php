<?php

namespace Formatters\Json;

function PrintResult(array $array): string
{
    return PrintResultJson($array);
}
function PrintResultJson(array $data): string
{
    return json_encode($data);
}
