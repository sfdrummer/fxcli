#!/usr/bin/env php

<?php

use Fx\Commands\BuildCommand;
use Fx\Commands\InitCommand;
use Fx\Commands\ListConfigCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
require 'vendor/autoload.php';

$app = new Application('FX Cli', '0.1');

$fileSystem = new Filesystem();

if (!$fileSystem->exists('fx.json')) {
  $app->add(new InitCommand());
} else {
  $app->add(new ListConfigCommand());
  $app->add(new BuildCommand());
}

$app->run();
