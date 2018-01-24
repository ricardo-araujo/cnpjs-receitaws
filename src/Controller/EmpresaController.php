<?php

namespace Forseti\Cnpjs\Controller;


use Forseti\Cnpjs\Model\Empresa;
use Forseti\Cnpjs\Utils\Utils;

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