<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;

include_once  __DIR__ . "./../401.php";

if($next){
    // Token and commit create
    $TOKEN_CREATE_NAME = 'name_create_token';
    $COMMIT_CREATE_NAME = 'Name_Create_Commit';

    // Token and commit delete
    $TOKEN_DELETE_NAME = 'name_delete_account_token';
    $COMMIT_DELETE_NAME = 'Name_Delete_Account_Commit';

    // Token and commit change
    $TOKEN_CHANGE_NAME = 'name_change_account_token';
    $COMMIT_CHANGE_NAME = 'Name_Change_Account_Commit';

    $post = $request->decode($post);
    include_once 'User/List.php';
    
    $strToken = $token->generate(32);
    Session::set($TOKEN_CREATE_NAME, $strToken);
    $tpl->merge($strToken, $TOKEN_CREATE_NAME);
    $tpl->merge($COMMIT_CREATE_NAME, 'name_create_commit');

    $strTokenDel = $token->generate(32);
    Session::set($TOKEN_DELETE_NAME, $strTokenDel);
    $tpl->merge($strTokenDel, $TOKEN_DELETE_NAME);
    $tpl->merge($COMMIT_DELETE_NAME, 'name_delete_account_commit');

    $strTokenChange = $token->generate(32);
    Session::set($TOKEN_CHANGE_NAME, $strTokenChange);
    $tpl->merge($strTokenChange, $TOKEN_CHANGE_NAME);
    $tpl->merge($COMMIT_CHANGE_NAME, 'name_change_account_commit');

    $tpl->setFile([
        'content'       => "{$thisModule}/list/index",
        'create'        => "{$thisModule}/list/create",
        'item'          => "{$thisModule}/list/item",
        'sub_script'    => "{$thisModule}/list/script",
        'sub_css'       => "{$thisModule}/list/css",
    ]);

    $tpl->assign([
        'name' => 'List',
        'liclass' => 'active'  
    ], 'breadcrumb');

    $tpl->merge('active', 'active_user');
    $tpl->merge('User manager', 'site_title');

    $urlUser = [
        'create'          => [K_URL_DASH, $thisModule, 'create'],
        'delete'          => [K_URL_DASH, $thisModule, 'delete'],
        'change'          => [K_URL_DASH, $thisModule, 'change'],
    ];
    
    foreach($urlUser as $key => $user){
        $tpl->merge(URL::create($user), 'url_user_' . $key);
    };
}