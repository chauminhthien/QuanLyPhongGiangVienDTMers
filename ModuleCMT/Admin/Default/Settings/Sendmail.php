<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

include_once __DIR__ . '/../404.php';

$sendmail = $model->getSettingOne(['type' => $type]);

if (isset($sendmail->id)) {
  $id         = $sendmail->id;
  $commitName = 'Setting_Name_Commit';
  $tokenName	= 'setting_name_token';
  $key	      = 'setting_key';

  $post = $request->decode($post);
  if (validate_token_commit($post, $tokenName, $commitName)){
    $response = ['message' => 'Data invalid.', 'status' => 'error', 'title' => 'Error'];
    $id = Session::get($key);

    $rules = [
      'id'        => ['type' => 'int', 'in' => [$id] ],
      'hostname'  => ['type' => 'string', 'min' => 3, 'max' => 100 ],
      'username'  => ['type' => 'email', 'min' => 3, 'max' => 100 ],
      'password'  => ['type' => 'string', 'min' => 7, 'max' => 100 ],
      'port'      => ['type' => 'int', 'min' => 0, 'max' => 9999 ],
      'secure'    => ['type' => 'string', 'min' => 3, 'max' => 100 ],
      'replyTo'   => ['type' => 'email', 'min' => 3, 'max' => 100 ],
      'email'     => ['type' => 'email', 'min' => 3, 'max' => 100 ],
      'name'      => ['type' => 'string', 'min' => 3, 'max' => 100 ],
    ];
  
    $columns = array_keys($rules);
    $validate = Validate::getInstance($rules, $columns)->setSource($post);
    $data = $validate->run();
    
    if ($validate->isFullValid()){
      $data = $request->encode($data);
      
      $dataSt = [
        'server' => [
          'hostname'  => $data['hostname'],
          'username'  => $data['username'],
          'password'  => $data['password'],
          'port'      => $data['port'],
          'secure'    => $data['secure'],
          'replyTo'   => $data['replyTo'],
        ],
        'from' => [
          'email'  => $data['email'],
          'name'  => $data['name'],
        ]
      ];
      $dt['extra'] = serialize($dataSt);
      $id       = $sendmail->id;
      $result   = $model->updateById($dt, $id);
      $response = ['message' => 'Update success.', 'status' => 'success', 'title' => 'Success'];
      
    } 
  
    $this->response = $response;
    new Response('Content-Type: application/json', function(){
        return $this->response;
    });
  
  }
  
  $strTokenName = $token->generate(32);
  Session::set($tokenName, $strTokenName);
  Session::set($key, $id);
  
  $tpl->merge($strTokenName, $tokenName);
  $tpl->merge($commitName, 'setting_name_commit');
  
  $sendmails = unserialize($sendmail->extra);
  $tpl->merge($sendmails, 'sendmail');
  $tpl->merge($sendmail, 'setting');
  
  $tpl->setFile([
    'content' 		  => $thisModule . '/' . $thisFolder . '/form',
  ]);
}