<?php
  defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

  $tpl->merge("You haven't permission ", 'site_title');
  $tpl->merge("You haven't permission ", 'page_name');
  
  $tpl->setFile([
    'content' => 'error/401'
  ]);