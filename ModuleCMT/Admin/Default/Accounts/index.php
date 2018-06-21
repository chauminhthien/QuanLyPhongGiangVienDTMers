<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\URL;

include_once  __DIR__ . "./../401.php";

if($next){
  $access_action = ['Avatar', 'Edit', 'Login', 'Logout', 'Profile', 'ForgotPassword', 'AccessForgot'];
  if (!in_array($action, $access_action)) URL::redirect($url_404);

  $tpl->assign([
    'name' => 'Accounts',
    'url' => URL::create($urlSidebar['accounts'])
  ], 'breadcrumb');

  $tpl->merge('User', 'page_name');
  $tpl->merge('active', 'active_user');

  $actionPath = $moduleDir . $action . '.php';
  if (!File::isFile($actionPath)) URL::redirect($url_404);
  include_once $actionPath;
}