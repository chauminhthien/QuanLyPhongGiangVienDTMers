<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\TB;
use Library\Mailer;

class Error404 extends PublicModel{
  function __construct(){
    parent::__construct();
  }
}
