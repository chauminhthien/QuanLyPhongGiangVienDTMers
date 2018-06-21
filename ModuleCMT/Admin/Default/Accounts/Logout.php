<?php  
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;

Session::remove(SESSION_LOGIN_ADMIN_KEY);
Session::remove(SESSION_ACCOUNT_ADMIN_KEY);
URL::redirect(URL::create([K_URL_DASH, 'accounts', 'login']));
die;