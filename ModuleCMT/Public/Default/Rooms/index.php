<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\URL;

include_once  __DIR__ . "./../404.php";

$access_action = ['Home', 'Filter', 'ResRooms'];
if (!in_array($action, $access_action)) return;// URL::redirect($url404) ;

// $thisModule = 'dashboard';
$tpl->merge('active', 'active_rooms');

$tpl->merge('Đăng ký phòng', 'page_name');

$tpl->merge('Đăng ký phòng', 'site_title');

$tpl->assign([
  'name' => 'Phòng',
  'url' => URL::create($urlSidebar['res_rooms'])
], 'breadcrumb');

$actionPath = __DIR__ . "/{$action}.php";
if (!File::isFile($actionPath)) return;// URL::redirect($url404) ;

include_once $actionPath;
