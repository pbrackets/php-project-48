<?php

namespace Formatters\Json;

function PrintResultJson(array $data): string
{
    return json_encode($data);
}
