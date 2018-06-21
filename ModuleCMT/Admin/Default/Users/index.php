<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\URL;


$access_action = ['Edit', 'Home', 'Create', 'Delete', 'Change', 'Permissions'];
if (!in_array($action, $access_action)) URL::redirect($url_404);

$tpl->assign([
  'name' => 'Users',
  'url' => URL::create($urlSidebar['user_list'])
], 'breadcrumb');

$tpl->merge('User', 'page_name');
$tpl->merge('active', 'active_user');
$tpl->merge('active', 'active_account');

$thisModule = 'users';

$actionPath = $moduleDir . $action . '.php';
if (!File::isFile($actionPath)) URL::redirect($url_404);
include_once $actionPath;
