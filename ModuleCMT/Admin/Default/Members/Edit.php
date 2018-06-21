<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Application\Response;
use Library\Validate;
include_once __DIR__ . '/../401.php';

if($next){
  include_once __DIR__ . '/../404.php';

  if (isset($dirURL[2])){
      $id = $dirURL[2];

      if (is_numeric($id)){
          $member = $model->getById($id);

          if(isset($member->id)){
              $tokenName = 'member_edit_token';
              $commitName = 'Member_Edit_Commit';
              $key = 'member_id';

              $post = $request->decode($post);
              if (validate_token_commit($post, $tokenName, $commitName)){
                  $response = ['message' => 'Data invalid.', 'status' => 'error'];
                  $flag = true;

                  $id = Session::get($key);
                  $rules = [
                    'id'         => ['type' => 'int', 'in' => [$id]],
                    'email'      => ['type' => 'string', 'base' => '/^[\w\.\-]+@([\w\-]{2,}\.){1,2}[A-Za-z]{2,}$/', 'min' => 7, 'max' => 100],
                    'phone'      => ['type' => 'string', 'base' => '/^\d{7,15}$/'],
                    'fullname'   => ['type' => 'string', 'min' => 3, 'max' => 200],
                    'gender'     => ['type' => 'int', 'in' => [0, 1]],
                    'birthday'   => ['type' => 'string', 'base' => '/^\d{2}\/\d{2}\/\d{4}$/'],
                ];
                  $columns = array_keys($rules);
              
                  $validate = Validate::getInstance($rules, $columns)->setSource($post);
                  $data = $validate->run();
              
                  if ($validate->isFullValid()){

                    

                    $email = $data['email'];

                    if($post['password'] && $post['password'] !== ''){
                        $flag = false;
                        $response['message'] = 'Password 6 - 32 characters';

                        $rules = ['password' => ['type' => 'string', 'min' => 6, 'max' => 32]];

                        $columns = array_keys($rules);
                        $dataFile = $validate->setRules($rules)->setPosition($columns)->setSource($post)->run();

                        if ($validate->isFullValid()){
                            $flag = true;
                            $data['password'] = $post['password'];
                        }
                    }
                    
                    if($flag){
                        $response['message'] = 'Email already exist.';
                        if(!$model->existEmailNotId($email, $id)){

                            list($m, $d, $y) = explode('/', $data['birthday']);
                            $data['birthday'] = mktime(0, 0, 0, $m, $d, $y);

                            $model->updateMemberById($id, $data);
                            $response = ['message' => 'Update successfully.', 'status' => 'success'];
                            
                        }
                    }
                  }

                  $this->response = $response;
                  new Response('Content-Type: application/json', function(){
                      return $this->response;
                  });
                  
              }

              $member->birthday = date('d/m/Y', $member->birthday);
              $tpl->merge($member,  'member');
              $tpl->merge('selected',  "member_selected_{$member->gender}");

              $strToken = $token->generate(32);
              Session::set($tokenName, $strToken);
              Session::set($key, $id);

              $tpl->merge($strToken, $tokenName);
              $tpl->merge($commitName, 'member_edit_commit');

              $tpl->merge($member->fullname, 'site_title');
              $tpl->merge('Edit  ' . $member->fullname, 'page_name');

              $tpl->assign([
                'name' => 'Edit',
                'liclass' => 'active'
              ], 'breadcrumb');

              $tpl->setFile([
                'content'       => 'members/edit',
                'sub_script'    => "{$thisModule}/script",
                'sub_css'       => "{$thisModule}/css",
                ]);
            }
          
      }
  }
}