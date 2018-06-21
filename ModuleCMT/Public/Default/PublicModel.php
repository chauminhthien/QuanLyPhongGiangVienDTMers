<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\TB;
use Library\Mailer;
use Library\Session;
use Application\Model;

class PublicModel extends Model{

    protected function __construct(){
        parent::__construct();
    }

    function getMyAccount(){
        $result = new stdClass;
        $id = Session::get(SESSION_ACCOUNT_PUBLIC_KEY);
 
        if ($id !== NULL) $result = $this->getOne(TB::MEMBERS, TB::F_MEMBERS, ['id' => $id]);
        return $result;
    }

    function getSetting(){
        return $this->getMany(TB::SETTING, TB::F_SETTING);
    }

    protected function updateById($table, array $data, $id){
        if(+$id > 0)
            return $this->update($table, $data,['id' => $id]);
        return NULL;
    }

    function sendMail(array $mail){
		$setting = $this->getOne(TB::SETTING, ['extra'], ['type' => 'sendmail']);

		if (isset($setting->extra)){
            $extra = unserialize($setting->extra);
            
			if (isset($extra['server'])){
					$server = $extra['server'];
                    
					if (isset($extra['from'])){
						$from = $extra['from'];
						if ($from){
                            $flag = true;
							if (!isset($server['hostname']) || !is_string($server['hostname'])) $flag = false;
							if (!isset($server['username']) || !is_string($server['username'])) $flag = false;
							if (!isset($server['password']) || !is_string($server['password'])) $flag = false;
							if (!isset($server['port']) || !is_string($server['port'])) $flag = false;
							if (!isset($server['secure']) || !is_string($server['secure'])) $flag = false;
							if (!isset($from['email']) || !is_string($from['email'])) $flag = false;
							if (!isset($from['name']) || !is_string($from['name'])) $flag = false;
							if (!isset($mail['subject']) || !is_string($mail['subject'])) $flag = false;
							if (!isset($mail['content']) || !is_string($mail['content'])) $flag = false;
							if (!isset($mail['email']) || !is_string($mail['email'])) $flag = false;
							if (!isset($mail['name']) || !is_string($mail['name'])) $flag = false;
                            
							if ($flag){
                                    $options = [];
                                    
									new Mailer(
											$mail['subject'], $mail['content'], 
											[$from['email'], $from['name']], 
											[[$mail['email'], $mail['name']]],
											[
													'host' => $server['hostname'], 
													'user' => $server['username'], 
													'pass' => $server['password'],
													'port'  => $server['port']
											], '', [], $options
									);
							}
						}
					}
				}
		}
	}


	function confirmString($string, $confirm){
        return is_string($string) && is_string($confirm) && strcmp($string, $confirm) === 0;
	}
	
}