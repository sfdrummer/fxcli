<?php

namespace Fx\Commands;

use Fx\Handlers\LibrariesHandler;
use Fx\Handlers\PartialsHandler;
use Fx\Handlers\VarsHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ListConfigCommand extends Command {

    protected $fs;
    protected $config;

    function __construct()
    {
        parent::__construct();
        $this->config = json_decode(file_get_contents(getcwd() . '/fx.json'));
    }

    public function configure()
    {
        $this->setName('listconfig')
             ->setDescription('Show a readable listing of the current configuration.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (isset($this->config->libraries)) {
            $output->writeln('<info>Libraries</info>');
            (new LibrariesHandler($this->config, $output, "/"))->list($output)->render();
            $output->writeln('');
        }
        if (isset($this->config->vars)) {
            $output->writeln('<info>Vars</info>');
            (new VarsHandler($this->config, $output, "/"))->list($output)->render();
            $output->writeln('');
        }
        if (isset($this->config->partials)) {
            $output->writeln('<info>Partials</info>');
            (new PartialsHandler($this->config, $output, "/"))->list($output)->render();
            $output->writeln('');
        }
    }
}
