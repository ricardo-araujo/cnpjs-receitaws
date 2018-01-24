<?php

namespace Forseti\Cnpjs\Command;

use Forseti\Cnpjs\Controller\EmpresaController;
use Forseti\Cnpjs\Request\ReceitaWS;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CapturaEmpresaCommand extends Command
{
    private $receitaRequest;

    public function __construct(ReceitaWS $request)
    {
        $this->receitaRequest = $request;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('empresa:captura')
            ->setDescription('Grava a empresa na base dados, atraves do cnpj')
            ->setDefinition([
                new InputArgument('collection', InputArgument::REQUIRED, 'Collection a ser inserida a(s) empresa(s)'),
                new InputArgument('cnpj', InputArgument::REQUIRED, 'Cnpj da empresa a ser buscada')
            ])
            ->setHelp(
<<<EOT
<info>php bin/console empresa:captura [collection] [cnpj]</info>
<comment>Busca a empresa pelo cnpj informado. Caso a empresa nao esteja na base, ela e inserida, senao, e atualizada (caso seus dados tenham mudado)</comment>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collection = $input->getArgument('collection');
        $cnpj = $input->getArgument('cnpj');

        $empresa = $this->receitaRequest->getEmpresa($cnpj);

        if ($empresa) {
            EmpresaController::store($collection, $empresa);
        }
    }
}