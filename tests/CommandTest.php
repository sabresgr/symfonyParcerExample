<?php

namespace App\Tests;
//include_once(__DIR__."/../config/constants.php");

use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testCommand()
    {

        $dir=__DIR__;
        $output="";
        exec("/usr/bin/php $dir/../bin/console app:parser $dir/TestFile --test ",$output,$result);

        $this->assertEquals(" Number of records: 1",$output[1]);
        $this->assertEquals("        Inserts:0  Updates: 0",$output[4]);
        $this->assertEquals(" [OK] Number of successful: 1",$output[3]);

    }


}
