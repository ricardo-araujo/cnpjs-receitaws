<?php

namespace Forseti\Cnpjs\Command;

use Forseti\Empresometro\Utils\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class CapturaTodosCnpjsCommand extends Command
{
    protected function configure()
    {
        $this->setName('empresa:todas')
            ->setDescription('Popula uma base MongoDB com todos os cnpjs possiveis')
            ->setDefinition([
                new InputArgument('collection', InputArgument::REQUIRED, 'Collection na qual serao inseridas as empresas')
            ])
            ->setHelp(
<<<EOT
<info>php bin/console empresa:todas [collection]</info>
<comment>Varre todos os cnpjs possiveis, valida-os e insere numa base de dados, caso nao tenham sido inseridos anteriormente</comment>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collection = $input->getArgument('collection');

        for ($cnpj = 1; $cnpj <= 99999999999999; $cnpj++) {

            $cnpj = Utils::fillStringWithChar($cnpj, 14, 0);

            if(Utils::cnpjIsValid($cnpj)) {
                $this->capturaEmpresaCommand($collection, $cnpj);
            }
        }
    }

    private function capturaEmpresaCommand($collection, $cnpj)
    {
        $command = $this->getApplication()->find('empresa:captura');

        $args = ['command' => 'empresa:captura', 'collection' => $collection, 'cnpj' => $cnpj];

        $inputArgs = new ArrayInput($args);

        $command->run($inputArgs, new NullOutput());
    }
}