<?php

use DivineOmega\CliProgressBar\ProgressBar;

require_once __DIR__.'/../vendor/autoload.php';

echo PHP_EOL;

$max = 250;

$progressBar = new ProgressBar;
$progressBar->setMaxProgress($max);

for ($i=0; $i < $max; $i++) { 
    usleep(200000);
    $progressBar->advance()->display();
}

$progressBar->complete();

echo PHP_EOL;