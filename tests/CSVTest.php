<?php

namespace Krbeasley\PhpCsv;

use Krbeasley\PhpCsv\CSV;
use PHPUnit\Framework\TestCase;

class CSVTest extends TestCase
{
    public function testCharIsEscaped()
    {
        $csv = new CSV(file_path: '');
        
        $true = "I really need\, this comma";
        $false = "but not, this one";
        $false2 = "and for this i care\\\, about the slashes";

        // echo "Assertation 1: " . $true . PHP_EOL;
        $this->assertTrue($csv->charIsEscaped($true, ','));
        // echo "Assertation 2: " . $false . PHP_EOL;
        $this->assertFalse($csv->charIsEscaped($false, ','));
        // echo "Assertation 3: " . $false2 . PHP_EOL;
        $this->assertFalse($csv->charIsEscaped($false2, ','));
    }

    public function testLineSplit()
    {
        $csv = new CSV(file_path: '');

        $test_string = "First Name, Last Name, Email, Phone Number";
        $exptected = ["First Name", "Last Name", "Email", "Phone Number"];

        $this->assertEquals($exptected, $csv->splitLine($test_string));
    }
}
