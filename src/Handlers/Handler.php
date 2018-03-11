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
    protected $output;
    protected $defaultTemplate;

    function __construct($config, $output)
    {
        $this->config = $config;
        $this->output = $output;
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

    public function build()
    {
        $this->print();
    }

    protected function configItems()
    {
        return $this->config[$this->section];
    }

    protected function getTemplate($key)
    {
        return array_key_exists($key, $this->templates) ? $this->templates[$key] : $this->defaultTemplate;
    }
}
