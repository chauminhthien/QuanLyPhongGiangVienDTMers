<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Application\Response;
use Library\Validate;

include_once __DIR__ . '/../404.php';

if (isset($dirURL[2])){
    $id = $dirURL[2];

    if (is_numeric($id)){
        $account = $model->getAccountById($id);
        
        if(isset($account->id)){
            $tokenName = 'accounts_edit_token';
            $commitName = 'Accounts_Edit_Commit';

            $post = $request->decode($post);
            if (validate_token_commit($post, $tokenName, $commitName)){
                $response = ['message' => 'Data invalid.', 'status' => 'error'];
                $flag = true;

                $rules = [
                    'email'      => ['type' => 'string', 'base' => '/^[\w\.\-]+@([\w\-]{2,}\.){1,2}[A-Za-z]{2,}$/', 'min' => 7, 'max' => 100],
                    'phone'      => ['type' => 'string', 'base' => '/^\d{7,15}$/'],
                    'code'       => ['type' => 'string', 'base' => '/^[a-z]{2}$/i'],
                    'id'         => ['type' => 'string', 'in' => [$id]],
                    'username'   => ['type' => 'string', 'min' => 3, 'max' => 200],
                    'gender'     => ['type' => 'int', 'in' => [0, 1]]
                ];
                $columns = array_keys($rules);
            
                $validate = Validate::getInstance($rules, $columns)->setSource($post);
                $data = $validate->run();
            
                if ($validate->isFullValid()){
                    $email = $data['email'];
                    if($post['password']){
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
                            $response['message'] = 'Code already exist.';
                            $code = strtoupper($data['code']);

                            if(!$model->existCode($code, $id)){
                                $data['code'] = $code;
                                $data['updated_by'] = $myAccount->id;
                                $data['updated_at'] = time();

                                $model->updateAccountById($id, $data);
                                $response = ['message' => 'Update successfully.', 'status' => 'success'];
                            }
                            
                        }
                    }
                }

                $this->response = $response;
                new Response('Content-Type: application/json', function(){
                    return $this->response;
                });
                
            }

            
            $tpl->merge($account,  'account');
            $tpl->merge('selected',  "acc_selected_{$account->gender}");

            $strToken = $token->generate(32);
            Session::set($tokenName, $strToken);

            $tpl->merge($strToken, $tokenName);
            $tpl->merge($commitName, 'accounts_edit_commit');

            $tpl->merge($account->username, 'site_title');
            $tpl->setFile(['content' => 'customer/edit']);
            }
        
    }
}