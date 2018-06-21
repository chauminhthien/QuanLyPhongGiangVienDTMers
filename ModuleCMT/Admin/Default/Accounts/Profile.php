<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

$commitNameInfo = 'Accounts_Change_Info';
$tokenNameInfo	= 'accounts_change_info_token';

$commitNamePass = 'Accounts_Change_Password';
$tokenNamePass	= 'accounts_change_password_token';

$tokenNameAvatar = 'account_avatar_token';
$commitNameAvatar = 'Account_Upload_Avatar';

$post = $request->decode($post);

if (validate_token_commit($post, $tokenNameInfo, $commitNameInfo)){ // change info
	$response = ['message' => 'Data invalid.', 'status' => 'error'];

	$rules = [
		'email'     	=> ['type' => 'string', 'in' => [$myAccount->email]],
		'fullname'  	=> ['type' => 'string', 'min' => 3, 'max' => 100],
		'gender'  		=> ['type' => 'string', 'min' => 0, 'max' => 1]
	];
	$columns = array_keys($rules);

	$validate = Validate::getInstance($rules, $columns)->setSource($post);
	$data = $validate->run();

	if ($validate->isFullValid()){
    $response['message'] = 'Update Error';
		$model->updateMyAccount($data);
		$response = ['message' => 'Update success.', 'status' => 'success', 'url' => $urlCurrent];
	}

	$this->response = $response;
	new Response('Content-Type: application/json', function(){
		return $this->response;
	});
}

if (validate_token_commit($post, $tokenNamePass, $commitNamePass)){ // change password
	$response = ['message' => 'Data invalid.', 'status' => 'error'];

	$rules = [
		'passwordCurrent'     	=> ['type' => 'string', 'min' => 6, 'max' => 32],
		'passwordNew'  					=> ['type' => 'string', 'min' => 6, 'max' => 32],
		'repasswordNew'  		=> ['type' => 'string', 'min' => 6, 'max' => 32],
	];
	$columns = array_keys($rules);

	$validate = Validate::getInstance($rules, $columns)->setSource($post);
	$data = $validate->run();
	
	if ($validate->isFullValid()){
    $response['message'] = 'Password invalid';

		if($model->confirmPassword($model->getHashPassWord($post['passwordCurrent']), $myAccount->password)){
			$response['message'] = 'Password Incorrect';
			
			if($model->confirmPassword($post['passwordNew'], $post['repasswordNew'])){
				$model->updateMyAccount(['password' => $post['passwordNew'] ]);
				$response = ['message' => 'Update success.', 'status' => 'success', 'url' => $urlCurrent];
			}
		}
	}

	$this->response = $response;
	new Response('Content-Type: application/json', function(){
		return $this->response;
	});
}

$strTokenInfo = $token->generate(32);
Session::add($tokenNameInfo, $strTokenInfo);
$tpl->merge($strTokenInfo, $tokenNameInfo);
$tpl->merge($commitNameInfo, 'accounts_change_info_commit');

$strTokenPass = $token->generate(32);
Session::add($tokenNamePass, $strTokenPass);
$tpl->merge($strTokenPass, $tokenNamePass);
$tpl->merge($commitNamePass, 'accounts_change_pass_commit');

$strTokenAvatar = $token->generate(32);
Session::add($tokenNameAvatar, $strTokenAvatar);
$tpl->merge($strTokenAvatar, $tokenNameAvatar);
$tpl->merge($commitNameAvatar, 'account_upload_avatar_commit');

$tpl->merge('Update', 'button_form');
$tpl->assign(['name' => 'Profile'], 'breadcrumb');
$tpl->merge('Profile My Account', 'site_title');
$tpl->merge('Account Profile', 'page_name');
$tpl->merge('active', 'active_profile');

$tpl->setFile([
    'content' 				=> 		"{$thisModule}/{$thisAction}/content",
    'left' 						=> 		"{$thisModule}/{$thisAction}/left",
		'right' 					=> 		"{$thisModule}/{$thisAction}/right",
		'right.info'			=> 		"{$thisModule}/{$thisAction}/right.info",
		'right.password'	=> 		"{$thisModule}/{$thisAction}/right.password",
]);

$tpl->merge(URL::create([K_URL_DASH, 'accounts', 'avatar']), 'url_account_avatar');
