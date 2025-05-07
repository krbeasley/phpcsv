<?php

$root = __DIR__;
$file_path = $root . '/../tests/' . $argv[1];

system('clear');
echo "Starting... $argv[0] with $file_path" . PHP_EOL;
echo "=================================================" . PHP_EOL . PHP_EOL;

require_once "$root/../vendor/autoload.php";

use Krbeasley\PhpCsv\CSV;
use Symfony\Component\Stopwatch\Stopwatch;

// Performance profiling
$stopwatch = new Stopwatch(true);
$stopwatch->start('runtime');

// Create the csv object
$stopwatch->start('read');
$csv = (new CSV($file_path))->read();
$perf_read = $stopwatch->stop('read');

// Get columns by index
$stopwatch->start('colByIndex');
$cols_by_index = $csv->getColumnValues(1);
$perf_col_by_index = $stopwatch->stop('colByIndex');

// Get headers
$stopwatch->start('headers');
$headers = $csv->getHeaders();
$perf_headers = $stopwatch->stop('headers');

// Get contents
$stopwatch->start('contents');
$contents = $csv->getContents();
$perf_contents = $stopwatch->stop('contents');

// Get columns by name
$header_name = $headers[2];
$stopwatch->start('colByName');
$cols_by_name = $csv->getNamedColumnValues($header_name);
$perf_col_by_name = $stopwatch->stop('colByName');

// Echo it all out (debugging)
// $stopwatch->start('echoing');
// echo "Values for column 2" . PHP_EOL;
// $i = 1;
// foreach ($cols_by_index as $row) {
//     echo "[$i] $row" . PHP_EOL;
//     $i++;
// }
// 
// echo PHP_EOL;
// echo "Headers" . PHP_EOL;
// $i = 0;
// foreach ($headers as $header) {
//     echo "[$i] $header" . PHP_EOL;
//     $i++;
// }
// 
// echo PHP_EOL;
// echo "Values for column named " . $csv->getHeaders()[2] . PHP_EOL;
// $i = 1;
// foreach ($cols_by_name as $row) {
//     echo "[$i] $row" . PHP_EOL;
//     $i++;
// }
// 
// $perf_echo = $stopwatch->stop('echoing');

// Stop the runtime timer
$perf_runtime = $stopwatch->stop('runtime');

echo PHP_EOL;
echo "Performance:" . PHP_EOL;
echo "Parsed a CSV with " . count($contents) . " rows." . PHP_EOL;

echo PHP_EOL;
echo "Reading: " . $perf_read . PHP_EOL;
echo "Cols by Index: " . $perf_col_by_index . PHP_EOL;
echo "Cols by Name: " . $perf_col_by_name . PHP_EOL;
echo "Get Headers: " . $perf_headers . PHP_EOL;
echo "Get Contnts: " . $perf_contents . PHP_EOL;
echo "Echoing: " . $perf_echo ?? "did not run" . PHP_EOL;
echo PHP_EOL;
echo "Runtime: " . $perf_runtime . PHP_EOL;

