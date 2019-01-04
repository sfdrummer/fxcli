<?php

namespace Fx\Handlers;

use Fx\Handlers\Handler;
use Symfony\Component\Console\Helper\Table;

/**
* Partials handler
*/
class PartialsHandler extends Handler
{
    protected $section = 'partials';
    protected $templates = [
        'medias' => 'MediaQueriesTemplate.twig',
        'fonts' => 'FontsTemplate.twig'
    ];
    protected $defaultTemplate = 'PartialsTemplate.twig';

    protected function print()
    {
        if (!$this->fs->exists($this->base . '/styles')) {
            $this->output->writeln('<error>Styles directory has not been created!</error>');
        }

        foreach ($this->config['partials'] as $key => $data) {
            $this->{$data['type']}($key, $data);
        }
    }

    private function command($key, $data)
    {
        chdir($this->base . '/styles');
        exec($data['command']);
        chdir('../../');
    }

    private function collection($key, $data)
    {
        // Create the collection directory
        $this->fs->mkdir($this->base . '/styles/' . $key);
        $this->output->writeln('<info>Creating collecting for ' . $key);

        // create files
        foreach ($data['partials'] as $partial) {
            $template = $this->twig->load($this->getTemplate($key));
            if (!$this->fs->exists($this->base . '/styles/' . $key . '/_' . $data['prefix'] . $partial . '.scss')) {
                $this->output->writeln('<info>Rendering: ' . $this->base . '/styles/' . $key . '/_' . $data['prefix'] . $partial . '.scss</info>');
                $this->fs->dumpfile($this->base . '/styles/' . $key . '/_' . $data['prefix'] . $partial . '.scss', $template->render(['filename' => $partial, 'config' => $data]));
            } else {
                $this->output->writeln('<info>Skipping: ' . $this->base . '/styles/' . $key . '/_' . $data['prefix'] . $partial . '.scss -> Already exists');
            }
        }
        $this->output->writeln('');
    }

    private function tree($key, $data)
    {
        $root = $this->base . '/styles/' . $key;
        $this->output->writeln('<info>Creating tree root for ' . $key);
        $this->fs->mkdir($root);

        foreach ($data['children'] as $child => $data) {
            if (!empty($data)) {
                $this->output->writeln('<info>Creating tree child for ' . $child . ' in ' . $key);
                $this->{$data['type']}($key . '/' . $child, $data);
            } else {
                $this->fs->mkdir($root . '/' . $child);
            }
        }
    }
}
