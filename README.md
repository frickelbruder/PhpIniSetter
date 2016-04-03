# Php-Ini-Setter
Add or update php.ini settings from the command line.

This tool is useful in an CI/CD environment to adjust the ini-settings according to your needs

##Installation

###Phar
Simply donwload the latest release at https://github.com/frickelbruder/php-ini-setter/releases

###Composer
```
php composer.phar global require "frickelbruder/php-ini-setter":"dev-master"
```

##Usage
```
sudo vendor/bin/change_ini_setting --name $settingToChange --value $valueToSetTheValueTo --file $pathToIniFile
```
- sudo is required, if your current user is not allowed to write to the ini file.
- vendor/bin/ is the path to your composer bin directory.

