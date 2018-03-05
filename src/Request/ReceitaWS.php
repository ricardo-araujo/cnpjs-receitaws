<?php

namespace Forseti\Cnpjs\Request;

use Forseti\Cnpjs\Utils\Utils;
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

    public function getEmpresa($cnpj, $tries = 3)
    {
        if (!Utils::cnpjIsValid($cnpj))
            return null;

        $cnpj = Utils::fillStringWithChar(Utils::onlyDigits($cnpj), 14, 0);

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
}