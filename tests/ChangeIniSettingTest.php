<?php
namespace ChangeIniSetting\Tests;

use ChangeIniSetting\ChangeIniSetting;

class ChangeIniSettingTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var ChangeIniSetting
   */
  private $TestClass = NULL;

  private $defaultTestFile = 'files/test.ini';

  private $iniContent = '';

  public function __construct() {
    $this->defaultTestFile = __DIR__ . '/' . $this->defaultTestFile;
  }

  public function setUp() {
    $this->TestClass = new ChangeIniSetting($this->defaultTestFile);
    $this->iniContent = file_get_contents($this->defaultTestFile);
  }

  public function testUpdateIniString() {
    $variableToUpdate = "variable1";
    $iniContent = "$variableToUpdate = testvalue1";
    $updatedValue = "testvalue2";


    $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

    $iniResult = parse_ini_string($result);
    $this->assertEquals($iniResult[$variableToUpdate], $updatedValue);
  }

  public function testUpdateIniStringWithQuotedValue() {
    $variableToUpdate = "variable1";
    $iniContent = "$variableToUpdate = 'testvalue1'";
    $updatedValue = "testvalue2";


    $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

    $iniResult = parse_ini_string($result);
    $this->assertEquals($iniResult[$variableToUpdate], $updatedValue);
  }

  public function testUpdateIniStringWithDoubleQuotedValue() {
    $variableToUpdate = "variable1";
    $iniContent = $variableToUpdate.' = "testvalue1"';
    $updatedValue = "testvalue2";


    $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

    $iniResult = parse_ini_string($result);
    $this->assertEquals($iniResult[$variableToUpdate], $updatedValue);
  }

  public function testUpdateIniStringWithUnknownKey() {
    $iniContent = "variable1 = testvalue1";
    $variableToUpdate = "variable5";
    $updatedValue = "testvalue2";

    $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

    $iniResult = parse_ini_string($result);
    $this->assertEquals($iniResult[$variableToUpdate], $updatedValue);
    $this->assertEquals($iniResult["variable1"], "testvalue1");
  }

}
