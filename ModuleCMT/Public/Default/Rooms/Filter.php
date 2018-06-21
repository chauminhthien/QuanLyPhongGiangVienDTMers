<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

include_once  __DIR__ . "./../404.php";

$commitName = 'Room_Filter_Commit';
$tokenName	= 'room_filter_token';

$post = $request->decode($post);

if (validate_token_commit($post, $tokenName, $commitName)){ // change info
	$response = ['message' => 'Dữ liệu không hợp lệ.', 'status' => 'error'];

  
	$rules = [
		'date_from'   => ['type' => 'string', 'base' => '/^\d{2}\/\d{2}\/\d{4}$/'],
		'date_to'  	  => ['type' => 'string', 'base' => '/^\d{2}\/\d{2}\/\d{4}$/'],
		'time_from'  	=> ['type' => 'string', 'base' => '/^\d{2}\:\d{2}$/'],
    'time_to'   	=> ['type' => 'string', 'base' => '/^\d{2}\:\d{2}$/']
  ];
	$columns = array_keys($rules);

	$validate = Validate::getInstance($rules, $columns)->setSource($post);
	$data = $validate->run();

	if ($validate->isFullValid()){
    $data = $request->encode($data);

    $flag         = true;
    $min_student  = NULL;

    if(isset($post['min_student']) && $post['min_student'] !== ''){
      $flag = false;
      $response['message'] = 'Số ghế lớn hơn 0 hoặc nhỏ hơn 999';
      $min_student = $post['min_student'];

      if(+$min_student > 0 && +$min_student <= 999) $flag = true;
    }
    
    if($flag){
      list($m, $d, $y)  = explode('/', $data['date_from']);
      $date_from        = mktime(0, 0, 0, $m, $d, $y);
      
      list($m, $d, $y)  = explode('/', $data['date_to']);
      $date_to          = mktime(23, 59, 59, $m, $d, $y);
      
      $response['message'] = 'Thời gian ít nhất một ngày';

      if($date_to > $date_from){
        $response['message'] = 'Chỉ được đăng ký phòng cho ngày mai';

        $timeCurrent = time();
        if($date_to >= $timeCurrent){
          list($h, $mi)  = explode(':', $data['time_from']);
          $time_from = ($h * 3600) + ($mi * 60);

          list($h, $mi)  = explode(':', $data['time_to']);
          $time_to = ($h * 3600) + ($mi * 60);

          $response['message'] = 'Thời gian không hợp lệ';

          if($time_to > $time_from){
            $result = $model->filterRoom($date_from, $date_to, $time_from, $time_to, $min_student, $myAccount->id);

            while($row = $model->fetch($result)){
              $rand = rand(1,3);
              $row->style = '';
              if($rand == 2) $row->style = 'scrl-dwn';
              if($rand == 3) $row->style = 'scrl-up';
              
              $tpl->assign($row, 'listRoom');
            }

            $data = [
              'date_from'   => $date_from,
              'date_to'     => $date_to,
              'time_from'   => $time_from,
              'time_to'     => $time_to,
              'min_student' => $min_student,
            ];

            $commitNameRes = 'Room_Res_Commit';
            $tokenNameRes	= 'room_res_token';

            $strTokenRes = $token->generate(32);
            Session::add($tokenNameRes, $strTokenRes);
            $tpl->merge($strTokenRes, $tokenNameRes);
            $tpl->merge($commitNameRes, 'room_res_commit');

            $tpl->merge(URL::create(['rooms', 'res-rooms']), 'url_room_res');

            $tpl->merge($data, 'data');

            $pathTheme = "{$this->themePath}/{$config->folder}/{$thisModule}/data.{$config->extension}";

            $response = [
              'message' => 'Tìm kiếm thành công.', 
              'status' => 'success',
              'action' => 'filter',
              'data' => $tpl->setTheme($pathTheme)->getContent()
            ];
          }

        }
      }
    }
	}

	$this->response = $response;
	new Response('Content-Type: application/json', function(){
		return $this->response;
	});
}
