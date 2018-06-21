<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\URL;

include_once __DIR__ . '/../404.php';
$tpl->merge('Page not found', 'page_name');
$tpl->assign([
  'name' => '404',
  'liclass' => 'active'  
], 'breadcrumb');