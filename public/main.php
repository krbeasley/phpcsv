<?php

$file_path = $argv[1];
$root = __DIR__;

system('clear');
echo "Starting... $argv[0] with $file_path" . PHP_EOL;
echo "=================================================" . PHP_EOL . PHP_EOL;

require_once "$root/../vendor/autoload.php";

use Krbeasley\PhpCsv\CSV;

$reader = (new CSV($file_path))->read();

var_dump($reader->headers());
