<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Token;
use Library\File;
use Library\URL;

date_default_timezone_set('Asia/Ho_Chi_Minh');
$token      = Token::getInstance();
$siteTitle  = 'dashboard_title';

$tpl = $this->template;
$tpl->merge($urlCurrent, 'url_current');
$tpl->merge($urlHome, 'url_home');
$tpl->merge($urlProject, 'url_project');

$pathURLTheme = URL::create([K_DIR_THEME, K_DIR_PUB, $this->theme]);
$pathURLModule = URL::create([K_DIR_MODULE, K_DIR_PUB]);

$tpl->merge($pathURLTheme, 'prefix_url_theme');

$tpl->setFile([
    'head'      => 'head',
    'body'      => 'body',
    'main'      => 'main',
    'menu'      => 'menu',
    'sidebar'   => 'sidebar',
    'breadcrumb'=> 'breadcrumb',
    'footer'    => 'footer',
    'script'    => 'script'
]);

$myAccount = $model->getMyAccount();

if (isset($myAccount->id)){
    $myAccount->{"gender_{$myAccount->gender}"} = "selected";
    $myAccount->birthday = date('d/m/Y', $myAccount->birthday);
    $tpl->merge($myAccount, 'myaccount');
}

$urlSidebar = [
    'accounts'          => ['accounts', 'profile'],
    'forfotPwd'         => ['accounts', 'forgot-password'],
    'login'             => ['accounts', 'login'],
    'logout'            => ['accounts', 'logout'],
    'profile'           => ['accounts', 'profile'],
    'res_rooms'         => ['rooms'],
];

foreach($urlSidebar as $key => $sidebar){//permissions
    $tpl->merge(URL::create($sidebar), 'url_' . $key);
};

$urlSetting = [
    'sendmail'          => ['type' => 'sendmail'],
];

foreach($urlSetting as $key => $setting){
    $tpl->merge(URL::create([K_URL_DASH, 'settings'], $setting), 'url_seting_' . $key);
};

$indexPath = $moduleDir . 'index.php';
if (!File::isFile($indexPath)) return;
include_once $indexPath;