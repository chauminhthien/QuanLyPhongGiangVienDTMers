<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\URL;
use Library\Validate;
use Application\Response;

$access_action = ['Home'];
if (!in_array($action, $access_action)) URL::redirect($url_404);


$actionPath = $moduleDir . $action . '.php';
if (!File::isFile($actionPath)) URL::redirect($url_404);
include_once $actionPath;