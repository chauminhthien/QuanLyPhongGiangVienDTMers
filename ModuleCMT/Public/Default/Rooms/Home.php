<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\URL;
use Library\Session;

$commitName = 'Room_Filter_Commit';
$tokenName	= 'room_filter_token';

$strToken = $token->generate(32);
Session::add($tokenName, $strToken);
$tpl->merge($strToken, $tokenName);
$tpl->merge($commitName, 'room_filter_commit');

$tpl->assign([
    'name' => 'Đăng ký',
    'liclass' => 'active'  
], 'breadcrumb');

$tpl->merge(URL::create(['rooms', 'filter']), 'url_room_filter');

$tpl->setFile([
    'content'     => "{$thisModule}/index",
    'sub_css'     => "{$thisModule}/style",
    'sub_script'  => "{$thisModule}/script",
]);
