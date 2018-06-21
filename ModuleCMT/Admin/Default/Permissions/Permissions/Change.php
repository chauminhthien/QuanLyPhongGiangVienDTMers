<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

if (validate_token_commit($post, $TOKEN_CHANGE_NAME, $COMMIT_CHANGE_NAME)){
    $response = ['message' => 'Data invalid.', 'status' => 'error'];

    $rules = [
        'name'       => ['type' => 'string', 'min' => 3, 'max' => 200],
        'key'        => ['type' => 'string', 'min' => 3, 'max' => 200],
        'id'         => ['type' => 'int', 'min' => 0],
        'link'       => ['type' => 'string', 'min' => 0, 'max' => 200],
        'ordering'   => ['type' => 'int', 'min' => 0],
        'parent'     => ['type' => 'int', 'min' => 0]
    ];
    $columns = array_keys($rules);

    $validate = Validate::getInstance($rules, $columns)->setSource($post);
    $data = $validate->run();

    if ($validate->isFullValid()){

        $id = $data['id'];
        $response['message'] = 'Account not exist';
        
        if($model->existPermission($id)){
            $response['message'] = "You haven't update permissions";
            
            if($myAccount->root){
                $model->updateById($data, $id);

                $response = [
                    'message'   => 'Update successfully.', 
                    'status'    => 'success',
                    'url'       => $urlCurrent
                ];
            }
            
        }
    }

    $this->response = $response;
    new Response('Content-Type: application/json', function(){
        return $this->response;
    });
}