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
            $output->writeln('<info>FX structure not detected, creating structure for "' . $this->config['info']['projectType'] . '"</info>');
            $output->writeln('');
            $this->initStructure();
        } else {
            $output->writeln('<info>FX structure detected!</info>');
            $output->writeln('');
        }

        if (isset($this->config)) {
            $output->writeln('<info>Building Libraries...</info>');
            $output->writeln('<info>=====================</info>');
            (new LibrariesHandler($this->config, $output, $this->getBase()))->build();
            $output->writeln('');
        }

        if (isset($this->config['vars'])) {
            $output->writeln('<info>Building Vars...</info>');
            $output->writeln('<info>================</info>');
            (new VarsHandler($this->config, $output, $this->getBase()))->build();
            $output->writeln('');
        }

        if (isset($this->config['partials'])) {
            $output->writeln('<info>Building Partials...</info>');
            $output->writeln('<info>====================</info>');
            (new PartialsHandler($this->config, $output, $this->getBase()))->build();
            $output->writeln('');
        }
    }

    private function initStructure()
    {
        switch ($this->config['info']['projectType']) {
            case 'drupal':
                $structure = $this->drupalStructure();
                break;

            case 'yeoman':
                $structure = $this->yeomanStructure();
                break;

            case 'patternlab':
                $structure = $this->patternLabStructure();
                break;

            case 'jigsaw':
                $structure = $this->jigsawStructure();
                break;

            default:
                die('Your selected structure is not valid!');
                break;
        }

        $this->fs->mkdir($structure);
    }

    private function getBase()
    {
        switch ($this->config['info']['projectType']) {
            case 'drupal':
                return 'assets';
                break;

            case 'yeoman':
                return 'app';
                break;

            case 'patternlab':
                return 'source';
                break;

            case 'jigsaw':
                return 'source';
                break;
        }
    }

    private function drupalStructure()
    {
        return [
            $this->getBase(),
            $this->getBase() . '/images',
            $this->getBase() . '/images/icons',
            $this->getBase() . '/images/misc',
            $this->getBase() . '/images/logos',
            $this->getBase() . '/styles',
            $this->getBase() . '/styles/vars',
            $this->getBase() . '/styles/utilities',
            $this->getBase() . '/os',
            $this->getBase() . '/compiled'
        ];
    }

    private function yeomanStructure()
    {
        return [
            $this->getBase(),
            $this->getBase() . '/images',
            $this->getBase() . '/images/icons',
            $this->getBase() . '/images/misc',
            $this->getBase() . '/images/logos',
            $this->getBase() . '/styles',
            $this->getBase() . '/styles/vars',
            $this->getBase() . '/styles/utilities',
            $this->getBase() . '/os',
            $this->getBase() . '/compiled'
        ];
    }

    private function patternLabStructure()
    {
        return [
            $this->getBase(),
            $this->getBase() . '/images',
            $this->getBase() . '/images/icons',
            $this->getBase() . '/images/misc',
            $this->getBase() . '/images/logos',
            $this->getBase() . '/styles',
            $this->getBase() . '/styles/vars',
            $this->getBase() . '/styles/utilities',
            $this->getBase() . '/images/os',
            $this->getBase() . '/images/compiled'
        ];
    }

    private function jigsawStructure()
    {
        return [
            $this->getBase(),
            $this->getBase() . '/assets/images',
            $this->getBase() . '/assets/images/icons',
            $this->getBase() . '/assets/images/misc',
            $this->getBase() . '/assets/images/logos',
            $this->getBase() . '/_assets/styles',
            $this->getBase() . '/_assets/styles/vars',
            $this->getBase() . '/_assets/styles/utilities',
            $this->getBase() . '/assets/images/os',
            $this->getBase() . '/assets/images/compiled'
        ];
    }
}
