<?php
define('VXVR_BS_ROOT', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
require_once(VXVR_BS_ROOT . 'vendor/autoload.php');

if (empty(ini_get('date.timezone'))) {
    date_default_timezone_set('UTC');
}

\VerteXVaaR\BlueSprints\Utility\Error::registerErrorHandler();
(new \VerteXVaaR\BlueSprints\Http\Request())->process()->respond();
