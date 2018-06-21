<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

if (validate_token_commit($post, $TOKEN_DELETE_NAME, $COMMIT_DELETE_NAME)){
        
    $response = ['message' => 'Data invalid.', 'status' => 'error'];

    $rules = ['id' => ['type' => 'int', 'min' => 1]];
    $columns = array_keys($rules);

    $validate = Validate::getInstance($rules, $columns)->setSource($post);
    $data = $validate->run();

    if ($validate->isFullValid()){
        $id = $data['id'];
        $response['message'] = "You haven't delele permissions";
        
        if($model->existPermission($id)){
            $model->deleteById($id);
            $response = [
                'message' => 'Delete successfully.', 
                'status' => 'success',
            ];
        }
    }

    $this->response = $response;
    new Response('Content-Type: application/json', function(){
        return $this->response;
    });
}