<?php

namespace Forseti\Cnpjs\Command;

use Forseti\Empresometro\Model\Cnpjs;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class CapturaEmpresasFromTableCommand extends Command
{
    protected function configure()
    {
        $this->setName('empresa:cnpjs')
            ->setDescription('Popula uma base MongoDB com os cnpjs provenientes de uma database')
            ->setDefinition([
                new InputArgument('tabela', InputArgument::REQUIRED, 'Tabela onde estao os cnpjs a serem buscados'),
                new InputArgument('coluna', InputArgument::REQUIRED, 'Nome da coluna da tabela onde estao os cnpjs'),
                new InputArgument('collection', InputArgument::REQUIRED, 'Collection na qual serao inseridas as empresas')
            ])
            ->setHelp(
<<<EOT
<info>php bin/console empresa:cnpjs [tabela] [coluna] [collection]</info>
<comment>Faz um select em uma tabela informada, que deve estar na mesma database informada no arquivo de configuracao. Alem disso, deve ser informada tambem, o nome da coluna em que estao inseridos os cnpjs.</comment>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tabela = $input->getArgument('tabela');
        $coluna = $input->getArgument('coluna');
        $coll = $input->getArgument('collection');

        $model = new Cnpjs();
        $model->setTable($tabela);

        $model->chunk(1000, function($cnpjs_collection) use($coll, $coluna) {
            foreach ($cnpjs_collection as $cnpjModel) {
                $this->capturaEmpresaCommand($coll, $cnpjModel->{$coluna});
            }
        });
    }

    private function capturaEmpresaCommand($collection, $cnpj)
    {
        $command = $this->getApplication()->find('empresa:captura');

        $args = ['command' => 'empresa:captura', 'collection' => $collection, 'cnpj' => $cnpj];

        $inputArgs = new ArrayInput($args);

        $command->run($inputArgs, new NullOutput());
    }
}