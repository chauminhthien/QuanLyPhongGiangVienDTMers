<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

include_once  __DIR__ . "./../404.php";

$commitName = 'Room_Res_Commit';
$tokenName	= 'room_res_token';

$post = $request->decode($post);

if (validate_token_commit($post, $tokenName, $commitName)){ // change info
	$response = ['message' => 'Dữ liệu không hợp lệ.', 'status' => 'error'];

  
	$rules = [
		'id'          => ['type' => 'int', 'min' => 1],
		'date_from'   => ['type' => 'string', 'base' => '/^\d{10}$/'],
		'date_to'  	  => ['type' => 'string', 'base' => '/^\d{10}$/'],
		'time_from'  	=> ['type' => 'string', 'base' => '/^\d{1,}$/'],
    'time_to'   	=> ['type' => 'string', 'base' => '/^\d{1,}$/'],
  ];
	$columns = array_keys($rules);

	$validate = Validate::getInstance($rules, $columns)->setSource($post);
  $data = $validate->run();
  
	if ($validate->isFullValid()){
    $data = $request->encode($data);
    $response['message'] = "Room already registered.";

    if(!$model->existRoom($data)){

      $response['message'] = 'You have registion it.';

      $idAccount = $myAccount->id;

      if(!$model->existRoom($data, $idAccount)){
        $response['message'] = 'Disconnect Server.';
        
        $data['member_id'] = $idAccount;
        $res = $model->createRoom($data);
     
        if($res > 0){

          $model->sendMail([
            'subject'   => 'Đăng Ký Phòng Thành Công',
            'content'   => 'Bạn Đã đăng ký phòng thành công. Xin vui lòng đợi email xác nhận của phòng quản lý.!',
            'email'     => $myAccount->email,
            'name'      => $myAccount->fullname
          ]);

          $response = [
            'message' => 'Registration room successfully.', 
            'status' => 'success',
            'action' => 'res_rooms',
            'data' => $data
          ];
        }
      }
      
    }
    
	}

	$this->response = $response;
	new Response('Content-Type: application/json', function(){
		return $this->response;
	});
}
