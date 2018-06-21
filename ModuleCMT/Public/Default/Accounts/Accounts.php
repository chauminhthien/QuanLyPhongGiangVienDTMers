<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\TB;
use Library\Session;

class Accounts extends PublicModel{

    final public function __construct(){
        parent::__construct();
    }

    function login($email, $passwd){
        $condition = ['email' => $email, 'status' => 1];
        $account = $this->getOne(TB::MEMBERS, ['id', 'email', 'password'], $condition);
        if (isset($account->password) && $this->confirmPassword($this->hashPassword($passwd), $account->password)){
            $this->accountID = $account->id;
            return true;
        }
        return false;
    }

    private function hashPassword($passwd){
        return md5(md5($passwd));
    }

    function confirmPassword($passwd, $confirm){
        return is_string($passwd) && is_string($confirm) && strcmp($passwd, $confirm) === 0;
    }

    function getHashPassWord($passwd){
        return $this->hashPassword($passwd);
    }

    final function updateMyAccount($data){
		$result = null; 
		$id = Session::get(SESSION_ACCOUNT_PUBLIC_KEY);
		$data = array_intersect_key($data, array_flip(TB::F_MEMBERS)); 
    
		if (!empty($data)) {
            if( isset($data['password'])) $data['password'] = $this->hashPassword($data['password']);
            $result = $this->updateById(TB::MEMBERS, $data, $id );
		}
		return $result;
    }
    

    final function getAllAccount($options = []){
        $condition = ['type' => 1];
        $options['sort'] = ['id' => 'desc'];
        return $this->getMany(TB::MEMBERS, TB::F_MEMBERS, $condition, $options);
    }

    function usedEmail($email){
        $condition = ['email' => $email];
        return $this->countId($condition);
    }

    function createAccount(array $data){
		$result = 0;
		$data = array_intersect_key($data, array_flip(TB::F_MEMBERS));
		if (!empty($data)){
            $data['password'] = $this->hashPassword($data['password']);

			$result = $this->insert(TB::MEMBERS, $data);
		}
		return $result;
    }
    
    function getAccountById($id){
		if(!!$id)
			return $this->getOne(TB::MEMBERS, TB::F_MEMBERS, ['id' => $id]);
		return null;
    }

    function getAccountByEmail($email){
		if(is_string($email))
			return $this->getOne(TB::MEMBERS, TB::F_MEMBERS, ['email' => $email]);
		return null;
    }
    
   

    function existAccount($id){
        $condition = ['id' => $id];
        return $this->countId($condition);
    }



    function updateAccountById($id, $data){
        $data = array_intersect_key($data, array_flip(TB::F_MEMBERS)); 
        
        if (!empty($data)) {
            if( isset($data['password'])) $data['password'] = $this->hashPassword($data['password']);
            $this->update(TB::MEMBERS, $data, ['id' => $id]);           
        }
    }

    private function countId($condition){
        $count = $this->count(TB::MEMBERS, 'id', $condition);
        return $count > 0;
    }

    function getAccountByCondition($condition){
        return $this->getOne(TB::MEMBERS, TB::F_MEMBERS, $condition);
    }

    function splitToken($strToken){
		$result = ['', ''];
	
		if (is_string($strToken)){
			$strlen = strlen($strToken);
			$active = substr($strToken, -42, 32);
			$id = substr($strToken, -($strlen - 10), $strlen - 62);
			
			if (preg_match('/^[1-9]\d*$/', $id) && preg_match('/^[A-Za-z\d]{32}$/', $active)){
				$result = [$id, $active];
			}
		}
	
		return $result;
    }

}