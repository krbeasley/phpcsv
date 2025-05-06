<?php

require_once "../vendor/autoload.php";

use krbeasley\phpcsv\CSV;

$file_path = $argv[1];

$reader = (new CSV($file_path))->read();

