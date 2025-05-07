<?php

$file_path = $argv[1];
$root = __DIR__;

system('clear');
echo "Starting... $argv[0] with $file_path" . PHP_EOL;
echo "=================================================" . PHP_EOL . PHP_EOL;

require_once "$root/../vendor/autoload.php";

use Krbeasley\PhpCsv\CSV;

$reader = (new CSV($file_path))->read();


for ($i = 0; $i < count($reader->getContents()); $i++) {
    $count = $i + 1;
    $line = $reader->getContents()[$i];
    $len = count($line);

    echo "[$count] ($len) " . implode(", ", $line) . PHP_EOL;
}
