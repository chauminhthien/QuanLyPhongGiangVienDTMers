<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

include_once __DIR__ . './../404.php';

$TOKEN_CHANGE_NAME = 'name_change_token';
$COMMIT_CHANGE_NAME = 'Name_Change_Commit';

if (validate_token_commit($post, $TOKEN_CHANGE_NAME, $COMMIT_CHANGE_NAME)){
    $response = ['message' => "You haven't permission", 'status' => 'error'];

    if($next){       
        $response['message'] = 'Data invalid.';

        $rules = [
            'id'            => ['type' => 'int', 'min' => 1],
            'name'          => ['type' => 'string', 'min' => 3, 'max' => 200],
            'max_student'   => ['type' => 'int', 'min' => 1, 'max' => 999]
        ];
        $columns = array_keys($rules);

        $validate = Validate::getInstance($rules, $columns)->setSource($post);
        $data = $validate->run();

        
        if ($validate->isFullValid()){
            $id     = $post['id'];
            $data   = $request->encode($data);

            $data['status'] = isset($post['status']) ? 1 : 0;
            $response['message'] = 'Room not exist';

            if($model->existById($id)){
                $response['message'] = "Disconnect Server";
        
                $model->updateById($id, $data);
                $response = [
                    'message' => 'Update successfully.', 
                    'status' => 'success',
                ];
                
                
            }
        }
    }

    $this->response = $response;
    new Response('Content-Type: application/json', function(){
        return $this->response;
    });
}