<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;


$tpl->setFile([
	'head' => "{$thisModule}/forgotpassword/head",
	'body' => "{$thisModule}/forgotpassword/init"
]);

if (isset($get['token'])){
    
	$activeToken = $get['token'];
	list($id, $strToken) = $model->splitToken($activeToken);

	$condition = ['id' => $id, 'token' => $strToken];
	$account = $model->getAccountByCondition($condition);

	if(isset($account->id) && $account->id == $id && $account->token == $strToken){
		$tpl->merge($account, 'account');


		$commitName = 'Accounts_Access_Forgot_Commit';
		$tokenName	= 'accounts_access_forgot_token';

		$post = $request->decode($post);
		if (validate_token_commit($post, $tokenName, $commitName)){
			$response = ['message' => 'Data invalid.', 'status' => 'error'];

			$rules = [
				'id' 							=> ['type' => 'int', 'in' => [$id]],
				'password' 				=> ['type' => 'string', 'min' => 6, 'max' => 32],
				'comfimpassword' 	=> ['type' => 'string', 'min' => 6, 'max' => 32]
			];
			$columns = array_keys($rules);

			$validate = Validate::getInstance($rules, $columns)->setSource($post);
			$data = $validate->run();

			if ($validate->isFullValid()){
				$response['message'] = 'Password and Comfimpassword no match.';

				if($model->confirmString($data['password'], $data['comfimpassword'])){
					$data['token'] = '';
					$model->updateAccountById($id, $data);

					$response = [
						'message' => 'Forgot success. Please check email.',
						'status'  => 'success',
						'url'			=> URL::create($urlSidebar['login'])
					];
				}

			}

			$this->response = $response;
			new Response('Content-Type: application/json', function(){
				return $this->response;
			});
		}

		$strToken = $token->generate(32);
		Session::add($tokenName, $strToken);
		$tpl->merge($strToken, $tokenName);
		$tpl->merge($commitName, 'accounts_access_forgot_commit');

		$tpl->setFile([
			'body' => "{$thisModule}/forgotpassword/access"
		]);
	}
}

$tpl->merge('Access Forgot Password', 'site_title');