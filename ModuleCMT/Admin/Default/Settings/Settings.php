<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\TB;
use Library\Session;

class Settings extends PublicModel{

  final public function __construct(){
      parent::__construct();
  }

  final public function getSettingOne(array $condition){
    if(!empty($condition))
        return $this->getOne(TB::SETTING,TB::F_SETTING, $condition);
    return NULL;
  }

  final function updateById(array $data, $id){
      $data = array_intersect_key($data, array_flip(TB::F_SETTING));
      if (!empty($data)){
          $this->update(TB::SETTING, $data, ['id' => $id]);
      }
  }

}