<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\URL;

include_once __DIR__ . '/../401.php';

if($next){
  include_once __DIR__ . '/../404.php';
  if (isset($get['type']) && is_string($get['type'])){
    $type         = $get['type'];
    $access_type  = ['link', 'logo', 'maps', 'sendmail', 'history', 'keymaps'];

    if(in_array($type, $access_type)){

      $action = ucfirst($type);

      $thisModule = 'settings';
      $thisFolder = $type;

      $tpl->merge('Setting Manager', 'title_site');
      $tpl->assign(['name' => 'Settings'], 'breadcrumb');

      $tpl->assign(['name' => 'Mailer', 'liclass' => 'active'], 'breadcrumb');

      $tpl->merge('active', 'active_setting');

      $typeAc = str_replace('-', '_', $type);
      $tpl->merge('active', "active_setting_{$typeAc}");

      $tpl->merge('Setting Mailer', 'page_name');
      $tpl->merge('Setting Mailer', 'site_title');

      $actionPath = __DIR__ . "/{$action}.php";
      if (!File::isFile($actionPath)) URL::redirect($url_404) ;
      include_once $actionPath;
    }
  }
}
