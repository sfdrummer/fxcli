<?php

namespace Fx\Commands;

use Fx\Handlers\LibrariesHandler;
use Fx\Handlers\PartialsHandler;
use Fx\Handlers\VarsHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class BuildCommand extends Command {

    protected $fs;
    protected $config;

    function __construct()
    {
        parent::__construct();
        $this->config = json_decode(file_get_contents(getcwd() . '/fx.json'), true);
        $this->fs = new Filesystem;
    }

    public function configure()
    {
        $this->setName('build')
             ->setDescription('Build the FX workspace from the stored configuration.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->fs->exists('assets')) {
            $output->writeln('<info>FX structure not detected, creating...</info>');
            $this->initStructure();
        } else {
            $output->writeln('<info>FX structure detected!</info>');
        }

        if (isset($this->config)) {
            $output->writeln('<info>Building Libraries...</info>');
            (new LibrariesHandler($this->config))->build($output);
            $output->writeln('');
        }

        if (isset($this->config['vars'])) {
            $output->writeln('<info>Building Vars...</info>');
            (new VarsHandler($this->config))->build($output);
            $output->writeln('');
        }

        if (isset($this->config['partials'])) {
            $output->writeln('<info>Building Partials...</info>');
            (new PartialsHandler($this->config))->build($output);
            $output->writeln('');
        }
    }

    private function initStructure()
    {
        $structure = [
            'assets',
            'assets/images',
            'assets/images/icons',
            'assets/images/misc',
            'assets/images/logos',
            'assets/styles',
            'assets/styles/vars',
            'assets/styles/utilities',
            'assets/touch',
            'assets/compiled'
        ];
        $this->fs->mkdir($structure);
    }
}
