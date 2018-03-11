<?php

namespace Fx\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class InitCommand extends Command {

    public function configure()
    {
        $this->setName('init')
             ->setDescription('Initialise configuration for a new instance of the FX workspace.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem;

        if(!$fs->copy(__DIR__ . '/../Config/fx.default.json', getcwd() . '/fx.json')) {
            $output->writeln('<info>Configuration initialised</info>');
        } else {
            $output->writeln('<error>Configuration initialisation failed.</error>');
        }
    }
}
