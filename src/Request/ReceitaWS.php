<?php

namespace Forseti\Cnpjs\Request;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

class ReceitaWS
{
    private $client;
    private $logger;

    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function getEmpresa($cnpj, $tries = 2)
    {
        if (!$this->isValidCnpj($cnpj))
            return null;

        $tries--;

        beggining:
        try {

            sleep(1);

            $this->logger->info("Pesquisando cnpj {$cnpj}", ['tentativa' => $tries]);

            $resp = $this->client->request('GET', "https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

            $requisicao = @json_decode($resp->getBody()->getContents(), true);

            $this->logger->info("Resultado retornado para o cnpj {$cnpj}", ['req' => $requisicao]);

            return ($requisicao['status'] == 'OK') ? $requisicao : null;

        } catch(\Exception $e) {

            $this->logger->error($e->getCode(), ['exception' => $e]);

            if (!$tries)
                return null;

            if ($e->getCode() === 429)
                sleep($tries * 10);

            $tries--;

            goto beggining;
        }
    }

    private function isValidCnpj($cnpj)
    {
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }
}