<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\TB;
use Library\Session;

class Rooms extends PublicModel{

    final public function __construct(){
        parent::__construct();
    }

    function countAllRoom(){
        return $this->count(TB::ROOMS, 'id');
    }

    private function countId($condition){
        $count = $this->count(TB::ROOMS, 'id', $condition);
        return $count > 0;
    }

    function createRoom(Array $data){
        $result = 0;
		$data = array_intersect_key($data, array_flip(TB::F_ROOMS));
		if (!empty($data)){
			$result = $this->insert(TB::ROOMS, $data);
		}
		return $result;
    }

    function getRoomById($id){
		if(!!$id)
			return $this->getOne(TB::ROOMS, TB::F_ROOMS, ['id' => $id]);
		return null;
    }

    function getByConditionOne($condition){
		return $this->getOne(TB::ROOMS, TB::F_ROOMS, $condition);
    }

    final function getAll($options = []){
        $options['sort'] = ['id' => 'desc'];
        return $this->getMany(TB::ROOMS, TB::F_ROOMS, [], $options);
    }

    final function existById($id){
        return $this->countId(['id' => $id]);
    }

    final function updateById($id, $data){
        $data = array_intersect_key($data, array_flip(TB::F_ROOMS));
        
        if (!empty($data)) {
            $this->update(TB::ROOMS, $data, ['id' => $id]);           
        }
    }

    function deleteById($id){
        return $this->delete(TB::ROOMS, ['id' => $id]);
    }

}