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
    protected $defaultTemplate = 'VarsTemplate.twig';

    protected function print()
    {
        if (!$this->fs->exists('assets/styles')) {
            $this->output->writeln('<error>Styles directory has not been created!</error>');
        }
        foreach ($this->configItems() as $key => $data) {
            $this->output->writeln('<info>Rendering: assets/styles/vars/_' . $key . '.scss</info>');
            $template = $this->twig->load($this->getTemplate($key));
            $this->fs->dumpfile('assets/styles/vars/_' . $key . '.scss',
                $template->render(['filename' => $key, 'config' => $data]));
        }
    }
}
