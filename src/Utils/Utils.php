<?php

namespace Forseti\Cnpjs\Utils;

class Utils
{
    private function __construct() {}

    public static function cnpjIsValid($cnpj)
    {
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function onlyDigits($string)
    {
        return preg_replace('#[^0-9]+#', '', $string);
    }

    public static function fillStringWithChar($string, $size, $char, $side = STR_PAD_LEFT)
    {
        return str_pad($string, $size, $char, $side);
    }
}