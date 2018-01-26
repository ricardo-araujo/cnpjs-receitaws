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
        });

        $result = Empresa::where($table, ['cnpj' => $collection->get('cnpj')])->get();

        if (empty($result)) {
            return Empresa::insert($table, $collection->toArray());
        }

        return false;
    }
}