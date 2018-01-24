<?php

namespace Forseti\Cnpjs\Controller;

use Forseti\Empresometro\Model\Empresa;
use Forseti\Empresometro\Utils\Utils;

class EmpresaController
{
    public static function store($table, array $empresa)
    {
        $collection = collect($empresa)->map(function($value, $key) {
            return ($key == 'cep'|| $key == 'cnpj') ? Utils::onlyDigits($value) : $value;
        })->toArray();

        if(empty(Empresa::where('cnpj', '=', $collection['cnpj'])->get())) {
            return Empresa::insert($table, $collection);
        }
    }
}