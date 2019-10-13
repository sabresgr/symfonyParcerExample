<?php

namespace App\Tests;
include(__DIR__."/../config/constants.php");
use PHPUnit\Framework\TestCase;
use App\Controller\ParcerController;
class ParcerControllerTest extends TestCase
{
    public function testFileRead()
    {
        $needData=Array (
            FN_PRODUCT_CODE => 'P0001',
            FN_PRODUCT_NAME => 'TV',
            FN_PRODUCT_DESCRIPTION => '32” Tv',
            FN_STOCK => '10',
            FN_COST => '399.99',
            FN_IS_DISCONT => '',);

        $parserController=new ParcerController();
        $data=$this->invokeMethod($parserController,"readFile",array(__DIR__.'/TestFile'));
        $this->assertEquals(array(0=>$needData),$data);

    }

    public function testValidation()
    {
        $inputData=Array (
        FN_PRODUCT_CODE => 'P0001',
        FN_PRODUCT_NAME => 'TV',
        FN_PRODUCT_DESCRIPTION => '32” Tv',
        FN_STOCK => '10',
        FN_COST => '399.99',
        FN_IS_DISCONT => '',);

        $parserController=new ParcerController();
        $validationData=$this->invokeMethod($parserController,"validateProduct",array($inputData));
        $this->assertEquals(0,count($validationData));
        $inputData[FN_COST]=10000;
        $validationData=$this->invokeMethod($parserController,"validateProduct",array($inputData));
        $this->assertEquals(1,count($validationData));
        $inputData[FN_COST]=-1;
        $validationData=$this->invokeMethod($parserController,"validateProduct",array($inputData));
        $this->assertEquals(1,count($validationData));
        $inputData[FN_COST]=1;
        $inputData[FN_STOCK]='many';
        $validationData=$this->invokeMethod($parserController,"validateProduct",array($inputData));
        $this->assertEquals(3,count($validationData));

    }

    public function testProcessFile()
    {
        $arRes=ParcerController::processFile(__DIR__.'/stock.csv');
        $this->assertEquals(11,count($arRes['valid']));
    }



    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }


}



