<?php
namespace ChangeIniSetting\Tests;

use ChangeIniSetting\ChangeIniSetting;

class AcceptanceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ChangeIniSetting
     */
    private $TestClass = NULL;

    private $defaultTestSourceFile = 'files/test.ini';

    private $defaultTestFile = 'tmp/test.ini';

    public function __construct()
    {
        parent::__construct();
        $this->defaultTestSourceFile = __DIR__ . '/' . $this->defaultTestSourceFile;
        $this->defaultTestFile = __DIR__ . '/' . $this->defaultTestFile;
    }

    protected function setUp() {
        $this->TestClass = new ChangeIniSetting();
        copy($this->defaultTestSourceFile, $this->defaultTestFile);
    }

    protected function tearDown() {
        unlink($this->defaultTestFile);
    }

    /**
     * @throws \Exception
     */
    public function testRoundTrip() {

        $this->TestClass->setFilePath($this->defaultTestFile);
        $this->TestClass->updateSetting('variable1', 'replaced');

        $fileData = file_get_contents($this->defaultTestFile);
        $this->assertContains('variable1 = replaced', $fileData);

        $ini = parse_ini_file($this->defaultTestFile);
        $this->assertEquals('replaced', $ini['variable1']);
    }

    public function testFileNotFoundThrowsException() {
        try {
            $this->TestClass->setFilePath($this->defaultTestFile . '__');
            $this->fail('No exception thrown');
        } catch(\Exception $exception) {
            $this->assertNotInstanceOf('\PHPUnit_Framework_Exception', $exception);
        }
    }
}