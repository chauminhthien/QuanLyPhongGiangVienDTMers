<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\File;
use Library\Validate;
use Library\Session;
use Library\URL;
use Application\Response;

include_once __DIR__ . '/../404.php';
$tokenNameAvatar = 'account_avatar_token';
$commitNameAvatar = 'Account_Upload_Avatar';

if ( isset($myAccount->id) && validate_token_commit($post, $tokenNameAvatar, $commitNameAvatar) && isset($files['avatar'])){
    $response = ['message' => 'Data invalid.', 'status' => 'error'];
    $avatar = $files['avatar'];
   
    $rules = [
        'name' => ['type' => 'arr', 'base' => '/^\[".+\.((jpg)|(png))"\]$/'],
        'type' => ['type' => 'arr', 'base' => '/^\["image\\\\\/((jpeg)|(png))"\]$/'],
        'size' => ['type' => 'arr', 'base' => '/^\[\d{1,7}\]$/'],
        'error' => ['type' => 'arr', 'base' => '/^\[0\]$/'],
        'tmp_name' => ['type' => 'arr', 'base' => '/^\[".+"\]$/']
    ];
    $columns = array_keys($rules);

    $validate = Validate::getInstance($rules, $columns)->setSource($avatar);
    $data = $validate->run();
    if ($validate->isFullValid()){
        $response['message'] = 'File size invalid';

        if ($avatar['size'][0] > 10 && $avatar['size'][0] < 2000000){
            $fileNames = explode('.', $avatar['name'][0]);
            $ext = array_pop($fileNames);

            $pathDir = K_ROOT . "Data/Uploads/Avatars";
            File::mkDir($pathDir);

            do{
                $name = $token->generate(20);
                $fullName = "{$pathDir}/{$name}.{$ext}";
            }while(File::isFile($fullName));
            //echo $fullName; die();
            @move_uploaded_file($avatar['tmp_name'][0], $fullName);
            $fullAvatar = "Data/Uploads/Avatars/{$name}.{$ext}";
            $fullAvatar = preg_replace('/\/+/', '/', $fullAvatar);
            $fullAvatar = preg_replace('/:\/+/', '://', $fullAvatar);

            $data = [
              'avatar'      => $fullAvatar,
            ];

            $model->updateMyAccount($data);

            if (!!$myAccount->avatar){
                $arrOld = explode('/', $myAccount->avatar);
                $oldName = array_pop($arrOld);

                $fileOld = K_ROOT . "Data/Uploads/Avatars/{$oldName}";
                File::remove($fileOld);
            }

            $response = [
                'message' => 'Change avatar successfully.', 
                'status' => 'success',
                'url_avatar' => "{$urlProject}/{$fullAvatar}",
                'id' => $myAccount->id
            ];
        }
    }

    $this->response = $response;
    new Response('Content-Type: application/json', function(){
        return $this->response;
    });
}
