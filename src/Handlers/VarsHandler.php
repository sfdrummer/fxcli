<?php

namespace Fx\Handlers;

use Fx\Handlers\Handler;
use Symfony\Component\Console\Helper\Table;

/**
* Vars handler
*/
class VarsHandler extends Handler
{
    protected $section = 'vars';
    protected $directory = 'vars';
    protected $templates = [
        'medias' => 'MediaQueriesTemplate.twig',
        'fonts' => 'FontsTemplate.twig'
    ];

    protected function print()
    {
        if (!$this->fs->exists('assets/styles')) {
            $output->writeln('<error>Styles directory has not been created!</error>');
        }
        foreach ($this->configItems() as $key => $data) {
            $template = $this->twig->load($this->getTemplate($key));
            $this->fs->dumpfile('assets/styles/vars/_' . $key . '.scss',
                $template->render(['filename' => $key, 'config' => $data]));
        }
    }

    private function getTemplate($key)
    {
        return array_key_exists($key, $this->templates) ? $this->templates[$key] : 'VarsTemplate.twig';
    }
}
