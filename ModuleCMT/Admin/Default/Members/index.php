<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\URL;


$access_action = ['Edit', 'Home', 'Change', 'Delete', 'Create'];
if (!in_array($action, $access_action)) URL::redirect($url_404);

$tpl->assign([
  'name' => 'Members',
  'url' => URL::create($urlSidebar['members'])
], 'breadcrumb');

$tpl->merge('Members', 'page_name');
$tpl->merge('active', 'active_members');
$tpl->merge('active', 'active_account');

$thisModule = 'members';

$urlMembers = [
  'change'          => [K_URL_DASH, $thisModule, 'change'],
  'delete'          => [K_URL_DASH, $thisModule, 'delete'],
  'create'          => [K_URL_DASH, $thisModule, 'create'],
];

foreach($urlMembers as $key => $sidebar){//permissions
  $tpl->merge(URL::create($sidebar), 'url_member_' . $key);
};

$actionPath = $moduleDir . $action . '.php';
if (!File::isFile($actionPath)) URL::redirect($url_404);
include_once $actionPath;
