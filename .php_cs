<?php
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

require 'vendor/autoload.php';

return Config::create()
    ->setRiskyAllowed(true)
    ->setRules(['@Symfony' => true])
    ->setFinder(Finder::create()->in(['src', 'tests']));
