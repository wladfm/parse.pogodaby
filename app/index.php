<?php

use Core\Main;

define('PATH_ROOT', getcwd() . DIRECTORY_SEPARATOR);
require("vendor/autoload.php");

Main::getInstance()->run();