<?php

namespace Differ\Differ;

function Diff($firstFileName, $secondFileName)
{
    if (!file_exists($firstFileName) || !file_exists($secondFileName)) {
        echo "Неверные пути до файлов\n";
    }
//прочитать содержимое файлов в переменные
    $firstFileContent  = file_get_contents($firstFileName);
    $secondFileContent = file_get_contents($secondFileName);

//преобразовать содержимое файлов в массив и присвоить в переменные
    $firstArray  = json_decode($firstFileContent, true);
    $secondArray = json_decode($secondFileContent, true);
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

    foreach ($secondArray as $skey => $svalue) {
        if (!array_key_exists($skey, $firstArray)) {
            $result ['+ ' . $skey] = $svalue;
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
