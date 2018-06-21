<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;

include_once  __DIR__ . "./../401.php";

if($next){
  $access_action = ['Home'];
  if (!in_array($action, $access_action)) return;// URL::redirect($url404) ;
  
  // $thisModule = 'dashboard';
  $tpl->merge('active', 'active_home');
  
  $tpl->merge('DashBoard', 'page_name');
  
  $tpl->merge('Dashboard', 'site_title');
  
  $actionPath = __DIR__ . "/{$action}.php";
  if (!File::isFile($actionPath)) return;// URL::redirect($url404) ;
  
  include_once $actionPath;
}
