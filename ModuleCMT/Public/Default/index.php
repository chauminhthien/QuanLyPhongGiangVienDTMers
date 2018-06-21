<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\Session;
use Library\URL;


Session::init();

define('SESSION_LOGIN_PUBLIC_KEY', 'HUONG_NGHIEP_DTM_LOGGED_PUBLIC');
define('SESSION_ACCOUNT_PUBLIC_KEY', 'HUONG_NGHIEP_DTM_ACCOUNT_PUBLIC_ID');

$module = 'Home';
$action = 'Home';

$urlHome 		= URL::create();
$urlCurrent = URL::getCurrent();
$urlSite 		= URL::getSiteName();
$url_404 		= URL::create(['404']);
$urlProject = URL::getProject();

if ($this->configIsFirstDir()){
	if (isset($dirURL[0])){
		$module = $dirURL[0];
		if (strtolower($module) == 'home') URL::redirect($urlHome);
	}
	if (isset($dirURL[1])){
		$action = $dirURL[1];
		if (strtolower($action) == 'home') URL::redirect(URL::create([$module]));
	}
}

$currentMudule = "{$module}_{$action}";
$next = true;

if (Session::get(SESSION_LOGIN_PUBLIC_KEY) === true){
	$deny_module = ['accounts_login', 'accounts_forgot-password', 'accounts_access-forgot'];
	if (in_array($currentMudule, $deny_module)) URL::redirect($urlHome);
}
else{
	$access_module = ['accounts_login', 'accounts_forgot-password', 'accounts_access-forgot'];
	if (!in_array($currentMudule, $access_module)){
		$next = false;
		$url = URL::create(['accounts', 'login'], ['return' => $urlCurrent]);
		URL::redirect($url);
	}
}

include_once 'Function.php';
include_once 'PublicModel.php';

if ($this->configRAU()){
	$module = RAU_string($module);
	$action = RAU_string($action);
}

$fileModel = $module;
if ($module == 'Home' && $action == 'Home') $fileModel = 'Main';

$urlFileModule = __DIR__ . "/{$module}/{$fileModel}.php";
if (!File::isFile($urlFileModule)) URL::redirect($url_404) ;

if($module == '404') $fileModel = 'Error404';
include_once $urlFileModule;

$model = new $fileModel();
$thisModule = strtolower($module);
$thisAction = strtolower($action);

$moduleDir = __DIR__ . "/{$module}/";

if (!File::isDir($moduleDir)) return ;