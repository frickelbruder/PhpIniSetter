<?php
namespace ChangeIniSetting;

class ChangeIniSetting {

  private $filePath = '';

  public function __construct($filePath) {
    $this->filePath = $filePath;
  }

  public function updateSetting($name, $value) {
    $iniContent = file_get_contents($this->filePath);

    $newString = $name . ' = ' . $value;

    $afterIniContent = $this->buildNewIniString($name, $iniContent, $newString);

    $this->saveNewIniFile($afterIniContent);
  }

  /**
   * @param $afterIniContent
   */
  private function saveNewIniFile($afterIniContent) {
    file_put_contents($this->filePath, $afterIniContent);
  }

  /**
   * @param $iniContent
   *
   * @return string
   */
  private function getBeforeValue($iniContent, $name) {
    $ini = parse_ini_string($iniContent);
    if(array_key_exists($name, $ini)) {
      return $ini[$name];
    }
    return null;
  }

  /**
   * @param $name
   * @param $iniContent
   * @param $newString
   *
   * @return string
   */
  private function buildNewIniString($name, $iniContent, $newString) {
    $beforeValue = $this->getBeforeValue($iniContent, $name);
    if (is_null($beforeValue)) {
      $afterIniContent = $iniContent . "\n" . $newString;
    }
    else {
      $pattern = '/' . preg_quote($name, '/') . '\s*=\s*(?:"\')?' . preg_quote($beforeValue, '/') . '(?:"\')?/';
      $afterIniContent = preg_replace($pattern, $newString, $iniContent);
    }
    return $afterIniContent;
  }


}

$opts = getopt("", array("file:", "name:", "value:"));

$changer = new ChangeIniSetting($opts["file"]);
$changer->updateSetting($opts["name"], $opts["value"]);

