<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\TB;
use Library\Session;

class Members extends PublicModel{
    private $id =  null;
    final public function __construct(){
        parent::__construct();
    }
    
    private function hashPassword($passwd){
        return md5(md5($passwd));
    }
    
    final function getAllMember($options = []){
        $options['sort'] = ['id' => 'desc'];
        return $this->getMany(TB::MEMBERS, TB::F_MEMBERS, [], $options);
    }

    function getMemberById($id){
		if(!!$id)
			return $this->getOne(TB::MEMBERS, TB::F_MEMBERS, ['id' => $id]);
		return null;
    }

    function existMember($id){
        $condition = ['id' => $id];
        return $this->countId($condition);
    }

    function deleteMember($id){
        return $this->delete(TB::MEMBERS, ['id' => $id]);
    }

    function updateMemberById($id, $data){
        $data = array_intersect_key($data, array_flip(TB::F_MEMBERS)); 
        
        if (!empty($data)) {
            if(isset($data['password'])) $data['password'] = $this->hashPassword($data['password']);
            $this->update(TB::MEMBERS, $data, ['id' => $id]);           
        }
    }

    private function countId($condition){
        $count = $this->count(TB::MEMBERS, 'id', $condition);
        return $count > 0;
    }

    function countAllMembers(){
        return $this->count(TB::MEMBERS, 'id');
    }

    function createMember(array $data){
		$result = 0;
		$data = array_intersect_key($data, array_flip(TB::F_MEMBERS));
		if (!empty($data)){
            $data['password'] = $this->hashPassword($data['password']);
			$result = $this->insert(TB::MEMBERS, $data);
		}
		return $result;
    }

    function getById($id){
		if(!!$id)
			return $this->getOne(TB::MEMBERS, TB::F_MEMBERS, ['id' => $id]);
		return null;
    }

    function usedEmail($email){
        $condition = ['email' => $email];
        return $this->countId($condition);
    }

    function existEmailNotId($email, $id){
        $condition = "`email` = '{$email}' AND `id` <> $id";
        return $this->countId($condition);
    }

}