<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

include_once __DIR__ . './../404.php';

$TOKEN_CHANGE_NAME = 'name_change_member_token';
$COMMIT_CHANGE_NAME = 'Name_Change_Member_Commit';

if (validate_token_commit($post, $TOKEN_CHANGE_NAME, $COMMIT_CHANGE_NAME)){
    $response = ['message' => "You haven't permission", 'status' => 'error'];

    if($next){       
        $response['message'] = 'Data invalid.';

        $rules = [
            'id'     => ['type' => 'int', 'min' => 1],
        ];
        $columns = array_keys($rules);

        $validate = Validate::getInstance($rules, $columns)->setSource($post);
        $data = $validate->run();

        if ($validate->isFullValid()){
            $id = $post['id'];
            $status = isset($post['status']) ? 1 : 0;
            $response['message'] = 'Member not exist';
            
            $dta = [
                'status'        => $status,
            ];

            if($model->existMember($id)){
                $response['message'] = "Disconnect Server";
        
                $model->updateMemberById($id, $dta);
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