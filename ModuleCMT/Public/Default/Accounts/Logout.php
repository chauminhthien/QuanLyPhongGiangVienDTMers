<?php  
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;

Session::remove(SESSION_LOGIN_PUBLIC_KEY);
Session::remove(SESSION_ACCOUNT_PUBLIC_KEY);
URL::redirect(URL::create(['accounts', 'login']));
die;