<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

$commitName = 'Accounts_Forgot';
$tokenName	= 'accounts_forgot_token';

$post = $request->decode($post);
if (validate_token_commit($post, $tokenName, $commitName)){
	$response = ['message' => 'Data invalid.', 'status' => 'error'];

	$rules = [
		'email' 		=> ['type' => 'email', 'min' => 7, 'max' => 100],
	];
	$columns = array_keys($rules);

	$validate = Validate::getInstance($rules, $columns)->setSource($post);
	$data = $validate->run();

	if ($validate->isFullValid()){
    $data = $request->encode($data);
    $response['message'] = 'Email not exist';
    $email = $data['email'];
    $account = $model->getAccountByEmail($email);

    if(isset($account->id)){
      $account = $request->decode($account);
      $id = $account->id;
      $tokenActive = $token->generate(32);
      $mailToken = $token->generate(10) . $id . $token->generate(10) . $tokenActive . $token->generate(10);

      $urlToken = URL::getSiteName() . URL::create([K_URL_DASH, 'accounts', 'access-forgot'], ['token' => $mailToken]);
      $model->updateAccountById($id, ['token' => $tokenActive]);

      Session::remove($tokenName);

      $model->sendMail([
        'subject'   => 'Forgot success. Please access email to continue.',
        'content'   => 'You forgot success account at chauminhthien.com site. Please active email to continue. <a href="' . $urlToken . '">Click here to active</a>',
        'email'     => $email,
        'name'      => $account->fullname
      ]);

      $response = [
        'message' => 'Forgot success. Please check email.',
        'status'  => 'success'
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
$tpl->merge($commitName, 'accounts_forgot_commit');

$tpl->setFile([
	'head' => "{$thisModule}/{$thisAction}/head",
	'body' => "{$thisModule}/{$thisAction}/forgotpassword"
]);

$tpl->merge($urlCurrent, 'url_main_form');
$tpl->merge('Forgot Password', 'site_title');