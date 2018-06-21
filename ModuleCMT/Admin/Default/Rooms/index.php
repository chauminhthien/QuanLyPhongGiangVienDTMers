<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\URL;


$access_action = ['Home', 'Create', 'Delete', 'Change'];
if (!in_array($action, $access_action)) URL::redirect($url_404);

$tpl->assign([
  'name' => 'Rooms',
  'url' => URL::create($urlSidebar['room'])
], 'breadcrumb');

$tpl->merge('active', 'active_cate');
$tpl->merge('active', 'active_cate_room');

$urlModule = [
  'delete'          => [K_DIR_DASH, $thisModule, 'delete'],
  'change'          => [K_DIR_DASH, $thisModule, 'change'],
];

foreach($urlModule as $key => $link){
  $tpl->merge(URL::create($link), 'url_room_' . $key);
};

$thisModule = 'rooms';

$actionPath = $moduleDir . $action . '.php';
if (!File::isFile($actionPath)) URL::redirect($url_404);
include_once $actionPath;
