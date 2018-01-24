<?php

namespace Forseti\Cnpjs\Model;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\Builder;

class Empresa
{
    /**
     * @param  string|array|\Closure  $column
     * @param  string|null  $operator
     * @param  mixed   $value
     * @param  string  $boolean
     * @return Builder
     */
    public static function where($table, $column, $operator = null, $value = null, $boolean = 'and')
    {
        return self::getInstance($table)->where($column, $operator, $value, $boolean);
    }

    public static function insert($table, $values)
    {
        return self::getInstance($table)->insert($values);
    }

    private static function getInstance($table)
    {
        return DB::connection('mongodb')->table($table);
    }
}