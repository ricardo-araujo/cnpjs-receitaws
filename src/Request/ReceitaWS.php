<?php

namespace Forseti\Cnpjs\Request;

use GuzzleHttp\ClientInterface;

class ReceitaWS
{
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getEmpresa($cnpj)
    {
        $tries = 2;

        beggining:
        try {

            $resp = $this->client->request('GET', "https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

            sleep(1);

            if ($resp->getStatusCode() !== 200)
                return null;

            $empresa = @json_decode($resp->getBody()->getContents(), true);

            return ($empresa['status'] == 'OK') ? $empresa : null;


        } catch(\Exception $e) {

            if (!$tries)
                return null;

            if ($e->getCode() === 429)
                sleep($tries * 10);

            $tries--;
            goto beggining;
        }
    }
}