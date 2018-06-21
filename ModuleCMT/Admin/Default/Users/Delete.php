<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Library\File;
use Application\Response;

include_once __DIR__ . './../404.php';

$TOKEN_DELETE_NAME = 'name_delete_account_token';
$COMMIT_DELETE_NAME = 'Name_Delete_Account_Commit';

if (validate_token_commit($post, $TOKEN_DELETE_NAME, $COMMIT_DELETE_NAME)){
    $response = ['message' => "You haven't permission", 'status' => 'error'];

    if($next){
        $response['message'] = 'Data invalid.';
        
        $rules = ['id' => ['type' => 'int', 'min' => 1, 'notin' => [$myAccount->id]]];
        $columns = array_keys($rules);

        $validate = Validate::getInstance($rules, $columns)->setSource($post);
        $data = $validate->run();

        if ($validate->isFullValid()){
            $id = $data['id'];

            $response['message'] = "You haven't delele permissions";
            $account = $model->getAccountById($id);
            if(isset($account->id) && $account->id == $id){

                $model->deleteAccount($id);
                if (!!$account->avatar){
                    $arrOld = explode('/', $account->avatar);
                    $oldName = array_pop($arrOld);

                    $fileOld = K_ROOT . "Data/Uploads/Avatars/{$oldName}";
                    File::remove($fileOld);
                }

                $response = [
                    'message'   => 'Delete successfully.', 
                    'status'    => 'success',
                ];
            }
        }
    }

    $this->response = $response;
    new Response('Content-Type: application/json', function(){
        return $this->response;
    });
}