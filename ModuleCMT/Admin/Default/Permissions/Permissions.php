<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));
use Library\TB;

class Permissions extends PublicModel{

    function __construct(){
        parent::__construct();
    }

    function getById($id){
      if(!!$id)
        return $this->getOne(TB::PERMISSIONS, TB::F_PERMISSIONS, ['id' => $id]);
      return null;
    }

    function create(array $data){
      $result = 0;
      $data = array_intersect_key($data, array_flip(TB::F_PERMISSIONS));
      if (!empty($data)){
        $result = $this->insert(TB::PERMISSIONS, $data);
      }
      return $result;
    }

    function updateById($data, $id){
      $data = array_intersect_key($data, array_flip(TB::F_PERMISSIONS));
      if (!empty($data)) {
        $this->update(TB::PERMISSIONS, $data, ['id' => $id]);           
      }
    }

    function countAll(){
      return $this->count(TB::PERMISSIONS, 'id');
    }

    final function getAll($options = []){
      $options['sort'] = ['id' => 'desc'];
      return $this->getMany(TB::PERMISSIONS, TB::F_PERMISSIONS, [], $options);
    }

    final function getPermissionParent($parent){
      $options = [
        'sort' => ['ordering' => 'ASC']
      ];

      if(+$parent >= 0)
        return $this->getMany(TB::PERMISSIONS, TB::F_PERMISSIONS, ['parent' => $parent], $options);
      return NULL;
    }


    final function existPermission($id){
      $condition = ['id' => $id];
      return $this->countId($condition);
    }

    final function deleteById($id){
      return $this->delete(TB::PERMISSIONS, ['id' => $id]);
    }

    private function countId($condition){
      $count = $this->count(TB::PERMISSIONS, 'id', $condition);
      return $count > 0;
  }
}