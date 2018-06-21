<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\Validate;
use Library\URL;
use Application\Response;

include_once __DIR__ . './../404.php';

$TOKEN_CREATE_NAME = 'name_create_token';
$COMMIT_CREATE_NAME = 'Name_Create_Commit';

$post = $request->decode($post);
if (validate_token_commit($post, $TOKEN_CREATE_NAME, $COMMIT_CREATE_NAME)){
    $response = ['message' => "You haven't permission", 'status' => 'error'];

    if($next){
        
        $response['message'] = 'Data invalid.';

        $rules = [
            'email'      => ['type' => 'string', 'base' => '/^[\w\.\-]+@([\w\-]{2,}\.){1,2}[A-Za-z]{2,}$/', 'min' => 7, 'max' => 100],
            'password'   => ['type' => 'string', 'min' => 6, 'max' => 32],
            'type'       => ['type' => 'string', 'base' => '/^([0,1])?$/'],
            'fullname'   => ['type' => 'string', 'min' => 3, 'max' => 200],
            'gender'     => ['type' => 'int', 'in' => [0, 1]]
        ];
        $columns = array_keys($rules);

        $validate = Validate::getInstance($rules, $columns)->setSource($post);
        $data = $validate->run();

        if ($validate->isFullValid()){
            $data = $request->decode($data);
        
            $response['message'] = 'Email already exist.';
            $email = $data['email'];

            if (!$model->usedEmail($email)){

                $data['created_by'] = $myAccount->id;
                $data['created_at'] = time();

                $idAcc = $model->createAccount($data);
                $acc = $model->getAccountById($idAcc);
                $acc->url_permissions = URL::create([K_URL_DASH, 'users', 'permissions', $acc->id]);
                $acc->checked = ($acc->status == 1) ? 'checked' : '';
                $acc->{"type_{$acc->type}"} = "selected";
                $tpl->assign($acc, 'listAccount');

                // Token and commit delete
                $TOKEN_DELETE_NAME = 'name_delete_account_token';
                $COMMIT_DELETE_NAME = 'Name_Delete_Account_Commit';

                // Token and commit change
                $TOKEN_CHANGE_NAME = 'name_change_account_token';
                $COMMIT_CHANGE_NAME = 'Name_Change_Account_Commit';

                $urlUser = [
                    'delete'          => [K_DIR_DASH, $thisModule, 'delete'],
                    'change'          => [K_DIR_DASH, $thisModule, 'change'],
                ];
                
                foreach($urlUser as $key => $user){
                    $tpl->merge(URL::create($user), 'url_user_' . $key);
                };
                
                $tpl->merge(Session::get($TOKEN_DELETE_NAME), $TOKEN_DELETE_NAME);
                $tpl->merge($COMMIT_DELETE_NAME, 'name_delete_account_commit');

                $tpl->merge(Session::get($TOKEN_CHANGE_NAME), $TOKEN_CHANGE_NAME);
                $tpl->merge($COMMIT_CHANGE_NAME, 'name_change_account_commit');

                $pathTheme = "{$this->themePath}/{$config->folder}/{$thisModule}/list/item.{$config->extension}";

                $response = [
                    'message' => 'Create successfully.', 
                    'status' => 'success',
                    'action' => 'create',
                    'data' => $tpl->setTheme($pathTheme)->getContent()
                ];
                

            }
        }
    }

    $this->response = $response;
    new Response('Content-Type: application/json', function(){
        return $this->response;
    });
}