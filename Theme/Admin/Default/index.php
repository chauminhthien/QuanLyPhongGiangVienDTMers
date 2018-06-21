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

$pathURLTheme = URL::create([K_DIR_THEME, K_DIR_DASH, $this->theme]);
$pathURLModule = URL::create([K_DIR_MODULE, K_DIR_DASH]);

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
    $tpl->merge($myAccount, 'myaccount');
    $permissions = $model->getPermissionsMyAccount($myAccount->permission_id, $myAccount->root);
    
    $root = $myAccount->root;
    
    $accessModule = ['Home_Home', 'accounts_profile', 'accounts_avatar', 'accounts_logout'];

    $next = ($root) ? true : false;
    if(in_array($currentMudule, $accessModule)) $next = true;

    while($pers = $model->fetch($permissions)){
        $tpl->box($pers->key);
        if(!$root){
            $linkAccess  = $urlHome . '/' . $pers->link;
            // $linkAccess  = preg_replace('/\/\/$/', '', $linkAccess);
   
            $urlPer      = substr($urlCurrent, 0, strlen($linkAccess));
            $linkAccess  = preg_replace('/\/{1,}$/', '', $linkAccess);

            if(!$next){    
                if($model->confirmString($linkAccess, $urlPer)){
                    $next = true;
                }
            }
        }
    }
}

$urlSidebar = [
    'accounts'          => [K_URL_DASH, 'accounts', 'profile'],
    'forfotPwd'         => [K_URL_DASH, 'accounts', 'forgot-password'],
    'login'             => [K_URL_DASH, 'accounts', 'login'],
    'logout'            => [K_URL_DASH, 'accounts', 'logout'],
    'profile'           => [K_URL_DASH, 'accounts', 'profile'],
    'user_list'         => [K_URL_DASH, 'users'],
    'members'           => [K_URL_DASH, 'members'],
    'permissions'       => [K_URL_DASH, 'permissions'],
    'room'              => [K_URL_DASH, 'rooms'],
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