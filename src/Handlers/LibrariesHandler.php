<?php

namespace Fx\Handlers;

use Fx\Handlers\Handler;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Filesystem\Filesystem;

/**
* Libraries handler
*/
class LibrariesHandler extends Handler
{
    protected $section = 'libraries';

    public function build()
    {
        $fs = new Filesystem;
        if (!$fs->exists('package.json')) {
            $fs->dumpFile('package.json', $this->npmDefaults($this->config));
            $this->output->writeln('<info>Created package.json file</info>');
            foreach ($this->config[$this->section] as $key => $data) {
                exec($data['manager'] . ' install --save ' . $data['packageName']);
            }
            $this->output->writeln('<info>Libraries initialised</info>');
        } else {
            $this->output->writeln('<info>Package manager already initialised</info>');
            $this->output->writeln('<info>Adding packages to package.json</info>');
            foreach ($this->config[$this->section] as $key => $data) {
                exec($data['manager'] . ' install --save ' . $data['packageName']);
            }
        }
    }

    private function npmDefaults()
    {
        return '{
            "name": "' . $this->config['info']['project'] .'",
            "version": "' . $this->config['info']['version'] . '",
            "main": "index.js",
            "scripts": {
                "test": "echo \"Error: no test specified\" && exit 1"
            },
            "keywords": [],
            "author": "' . $this->config['info']['author'] .'",
            "license": "' .$this->config['info']['licence'] . '",
            "description": "' .$this->config['info']['description'] . '"
        }';
    }

    protected function prepareDirectories()
    {

    }

    protected function writeFiles()
    {

    }
}
