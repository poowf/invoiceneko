<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('storage/framework')
    ->exclude('bootstrap/cache')
    ->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder);
