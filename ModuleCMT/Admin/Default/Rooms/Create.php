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
            'name'          => ['type' => 'string', 'min' => 3, 'max' => 200],
            'max_student'   => ['type' => 'int', 'min' => 1, 'max' => 999],
        ];
        $columns = array_keys($rules);

        $validate = Validate::getInstance($rules, $columns)->setSource($post);
        $data = $validate->run();

        if ($validate->isFullValid()){
            $data = $request->decode($data);
            $data['name'] = strtoupper($data['name']);

            $response['message'] = 'Room name exist.';

            $resule = $model->getByConditionOne(['name' => $data['name']]);

            if(!isset($resule->id)){
                $response['message'] = 'Disconnect Server.';

                $id             = $model->createRoom($data);
                $room           = $model->getRoomById($id);
                $room->checked  = ($room->status) ? 'checked' : '';
                $tpl->assign($room, 'listRoom');

                // Token and commit delete
                $TOKEN_DELETE_NAME = 'name_delete_token';
                $COMMIT_DELETE_NAME = 'Name_Delete_Commit';

                // Token and commit change
                $TOKEN_CHANGE_NAME = 'name_change_token';
                $COMMIT_CHANGE_NAME = 'Name_Change_Commit';
                
                $tpl->merge(Session::get($TOKEN_DELETE_NAME), $TOKEN_DELETE_NAME);
                $tpl->merge($COMMIT_DELETE_NAME, 'name_delete_commit');

                $tpl->merge(Session::get($TOKEN_CHANGE_NAME), $TOKEN_CHANGE_NAME);
                $tpl->merge($COMMIT_CHANGE_NAME, 'name_change_commit');

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