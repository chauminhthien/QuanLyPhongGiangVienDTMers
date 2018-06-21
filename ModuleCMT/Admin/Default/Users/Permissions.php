<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

include_once  __DIR__ . "./../401.php";

if($next){

    include_once __DIR__ . './../404.php';

    if (isset($dirURL[2]) && is_numeric($dirURL[2]) && +$dirURL[2] > 0){
        $id = $dirURL[2];

        $user = $model->getAccountById($id);

        if(isset($user->id) && $user->id == $id && !$user->root){
            $permissions = $user->permission_id;
            $TOKEN_UPDATE_NAME = 'user_update_permission_token';
            $COMMIT_UPDATE_NAME = 'User_Update_Permission_Commit';
            $USER_ID = 'userID';


            if (validate_token_commit($post, $TOKEN_UPDATE_NAME, $COMMIT_UPDATE_NAME)){

                $response = ['message' => 'Data invalid.', 'status' => 'error'];
                $idUs = Session::get($USER_ID);
    
                $rules = [
                    'id'    => ['type' => 'string', 'in' => [$idUs]]
                ];
                $columns = array_keys($rules);

                $validate = Validate::getInstance($rules, $columns)->setSource($post);
                $data = $validate->run();
                
                if ($validate->isFullValid()){
                    $dataN['permission_id'] = '';
                    $flag = true;

                    if(isset($post['per'])){
                        $flag = false;
                        
                        $rules = [
                            'per'   => ['type' => 'arr', 'base' => '/^\["(\d){1,}"(\,"(\d){1,}")*\]$/'],
                        ];

                        $columns = array_keys($rules);
                        $per = $validate->setRules($rules)->setPosition($columns)->setSource($post)->run();
                        if ($validate->isFullValid()){
                            $flag = true;
                            $dataN['permission_id'] = implode(',', $per['per']);
                        }
                    }

                    if($flag){
                        $model->updateAccountById($idUs, $dataN);
                        $response = ['message' => 'Update success.', 'status' => 'success'];
                    }
                }
                $this->response = $response;
                new Response('Content-Type: application/json', function(){
                    return $this->response;
                });
            }

            $permissions = explode(',', $permissions);
            foreach($permissions as $per){
                $tpl->merge('checked', "per_checked_{$per}");
            };
            
            $strToken = $token->generate(32);
            Session::set($TOKEN_UPDATE_NAME, $strToken);
            $tpl->merge($strToken, $TOKEN_UPDATE_NAME);
            $tpl->merge($COMMIT_UPDATE_NAME, 'user_update_permission_commit');
            
            $tpl->merge($user, 'user');
            // prr($tpl); die();
            Session::set($USER_ID, $id);

            $parentPers = $model->getPermissionParent(0);
            while($row1 = $model->fetch($parentPers, true)){
                $tpl->assign($row1, 'parentPers');
                $subPer = $model->getPermissionParent($row1->id);
                while($sub = $model->fetch($subPer)){
                    $tpl->assign($sub, 'parentPers.subPers');
                    $pers = $model->getPermissionParent($sub->id);
                    while($p = $model->fetch($pers)){
                        $tpl->assign($p, 'parentPers.subPers.pers');
                    }
                }
            }
            

            $tpl->setFile([
                'content'       => "{$thisModule}/permissions/index",
                'sub_script'    => "{$thisModule}/permissions/script",
            ]);

            $tpl->assign([
                'name' => 'Permissions',
                'liclass' => 'active'  
            ], 'breadcrumb');

            $tpl->merge('active', 'active_user');
            $tpl->merge('User manager', 'site_title');
        }
        
    }
}

