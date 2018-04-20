# ⏱ PHP CLI Progress Bar

Progress bar for command line PHP scripts.

<img alt="Example of PHP CLI Progress Bar" src="assets/images/php-cli-progress-bar-example.gif" />

## Installation

To install, just run the following Composer command.

```
composer require divineomega/php-cli-progress-bar
```

## Usage

The following code snippet shows a basic usage example.

```php
$max = 250;

$progressBar = new DivineOmega\CliProgressBar\ProgressBar;
$progressBar->setMaxProgress($max);

for ($i=0; $i < $max; $i++) { 
    usleep(200000); // Instead of usleep, process a part of your long running task here.
    $progressBar->advance()->display();
}

$progressBar->complete();
```
