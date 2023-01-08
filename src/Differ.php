<?php

namespace Differ;

use function Parsers\parse;

function Diff($firstFileName, $secondFileName): array
{
    if (!file_exists($firstFileName) || !file_exists($secondFileName)) {
        echo "Неверные пути до файлов\n";
        return [];
    }
//прочитать содержимое файлов в переменные
    $firstFileContent  = file_get_contents($firstFileName);
    $secondFileContent = file_get_contents($secondFileName);

    $firstFileExtension = pathinfo($firstFileName, PATHINFO_EXTENSION);
    $secondFileExtension = pathinfo($secondFileName, PATHINFO_EXTENSION);

//преобразовать содержимое файлов в массив и присвоить в переменные

    $firstArray  = parse($firstFileContent, $firstFileExtension);
    $secondArray = parse($secondFileContent, $secondFileExtension);
    //var_dump($firstArray);
    //var_dump($secondArray);
    $result = [];

    foreach ($firstArray as $key => $value) {
        if (array_key_exists($key, $secondArray)) {
            if ($value === $secondArray[$key]) {
                $result ['  ' . $key] = $value;
            } else {
                $result ['- ' . $key] = $value;
                $result ['+ ' . $key] = $secondArray[$key];
            }
        } else {
            $result ['- ' . $key] = $value;
        }
    }

    foreach ($secondArray as $key1 => $value1) {
        if (!array_key_exists($key1, $firstArray)) {
            $result ['+ ' . $key1] = $value1;
        }
    }

    //сортировка по ключам
    uksort($result, function ($a, $b) {
        $a = trim($a, '+ -');
        $b = trim($b, '+ -');
        if ($a == $b) { // если 2 значения массива равны
            return 0; // вернем 0
        }
        return ($a < $b) ? -1 : 1;
    });

    return $result;
}
