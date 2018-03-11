<?php

namespace Fx\Handlers;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Filesystem\Filesystem;

/**
* Handler
*/
class Handler
{
    protected $config;
    protected $fs;
    protected $section;
    protected $directory;
    protected $twig;

    function __construct($config)
    {
        $this->config = $config;
        $this->fs = new Filesystem;
        if ($this->fs->exists(__DIR__ . '/../Templates/' . $this->section)) {
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Templates/' . $this->section . '/');
            $this->twig = new \Twig_Environment($loader);
        } else {
            $this->twig = false;
        }
    }

    public function list($output)
    {
        $table = new Table($output);
        $table->setHeaders(['Type', 'Data']);
        $rows = [];
        foreach ($this->config->{$this->section} as $key => $data) {
            $rows[] = [$key, json_encode($data)];
        }
        $table->setRows($rows);

        return $table;
    }

    public function build($output)
    {
        $output->writeln("<info>{$this->section}: Printing configuration...");
        $this->print($output);
    }

    protected function configItems()
    {
        return $this->config[$this->section];
    }
}
