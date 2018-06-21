<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

$commitName = 'Accounts_Login';
$tokenName	= 'accounts_login_token';

$post = $request->decode($post);
if (validate_token_commit($post, $tokenName, $commitName)){
	$response = ['message' => 'Data invalid.', 'status' => 'error'];

	$rules = [
		'email' 		=> ['type' => 'email', 'min' => 7, 'max' => 100],
		'password' 	=> ['type' => 'string', 'min' => 6, 'max' => 32]
	];
	$columns = array_keys($rules);

	$validate = Validate::getInstance($rules, $columns)->setSource($post);
	$validate->run();

	if ($validate->isFullValid()){
        $response['message'] = 'Username or Password incorrect.';

		if ($model->login($post['email'], $post['password'])){
			Session::set(SESSION_LOGIN_PUBLIC_KEY, true);
			Session::set(SESSION_ACCOUNT_PUBLIC_KEY, $model->accountID);
			Session::remove($tokenName);

      $redirect = (isset($get['return']) && is_string($get['return'])) ? $get['return'] : $urlHome;
      $response = ['message' => 'Login success.', 'status' => 'success', 'url' => $redirect];
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
$tpl->merge($commitName, 'accounts_login_commit');

$tpl->setFile([
	'head' => "{$thisModule}/{$thisAction}/head",
	'body' => "{$thisModule}/{$thisAction}/body"
]);

$tpl->merge($urlCurrent, 'url_main_form');
$tpl->merge('Login to Dashboard', 'site_title');