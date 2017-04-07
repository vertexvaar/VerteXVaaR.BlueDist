<?php

if (!file_exists('css')) {
    symlink('../../vendor/vertexvaar/bluesprints/resources/public/css/', 'css');
}

define('VXVR_BS_ROOT', dirname(dirname(realpath(__DIR__))) . DIRECTORY_SEPARATOR);
require('../../vendor/autoload.php');
require('../../vendor/vertexvaar/bluesprints/resources/public/index.php');
