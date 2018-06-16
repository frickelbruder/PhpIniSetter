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
      parent::__construct();
    $this->defaultTestFile = __DIR__ . '/' . $this->defaultTestFile;
  }

  public function setUp() {
    $this->TestClass = new ChangeIniSetting();
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

    public function testUpdateIniStringDoesNotUpdateComment() {
        $variableToUpdate = "variableWithComment";
        $iniContent = $this->iniContent;
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

    public function testUpdateIniStringWithOffValue() {
        $iniContent = "variable1 = Off";
        $variableToUpdate = "variable1";
        $updatedValue = "On";

        $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

        $iniResult = parse_ini_string($result);
        $this->assertEquals($iniResult[$variableToUpdate], "1");
    }

    public function testUpdateIniStringWithEmptyValue() {
        $iniContent = "variable1 = ";
        $variableToUpdate = "variable1";
        $updatedValue = "On";

        $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

        $iniResult = parse_ini_string($result);
        $this->assertEquals($iniResult[$variableToUpdate], "1");
    }

    public function testUpdateIniStringWithNoValue() {
        $iniContent = "variable1 = No";
        $variableToUpdate = "variable1";
        $updatedValue = "On";

        $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

        $iniResult = parse_ini_string($result);
        $this->assertEquals($iniResult[$variableToUpdate], "1");
    }

    public function testUpdateIniStringWithOnValue() {
        $iniContent = "variable1 = On";
        $variableToUpdate = "variable1";
        $updatedValue = "Off";

        $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

        $iniResult = parse_ini_string($result);
        $this->assertEquals($iniResult[$variableToUpdate], "");
    }

    public function testUpdateIniStringWithYesValue() {
        $iniContent = "variable1 = Yes";
        $variableToUpdate = "variable1";
        $updatedValue = "Off";

        $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

        $iniResult = parse_ini_string($result);
        $this->assertEquals($iniResult[$variableToUpdate], "");
    }

    public function testUpdateIniStringRecognizesAndUpdatesYesValue() {
        $iniContent = "variable1 = Yes";
        $variableToUpdate = "variable1";
        $updatedValue = "Off";

        $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

        $this->assertEquals($result, "variable1 = Off");
    }
    public function testUpdateIniStringRecognizesAndUpdatesCaseInsensitivYesValue() {
        $iniContent = "variable1 = yEs";
        $variableToUpdate = "variable1";
        $updatedValue = "Off";

        $result = $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

        $this->assertEquals($result, "variable1 = Off");
    }

    public function testDebugModeOnReturnsMessages() {
        $iniContent = "variable1 = yEs";
        $variableToUpdate = "variable1";
        $updatedValue = "Off";

        $this->TestClass->setDebugMode(true);
        $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

        $this->assertTrue(is_array($this->TestClass->getDebugMessages()));
        $this->assertNotEmpty($this->TestClass->getDebugMessages());
    }

    public function testDebugModeOffReturnsNoMessages() {
        $iniContent = "variable1 = yEs";
        $variableToUpdate = "variable1";
        $updatedValue = "Off";

        $this->TestClass->setDebugMode(false);
        $this->TestClass->updateIniString($variableToUpdate, $updatedValue, $iniContent);

        $this->assertTrue(is_array($this->TestClass->getDebugMessages()));
        $this->assertEmpty($this->TestClass->getDebugMessages());
    }

}
