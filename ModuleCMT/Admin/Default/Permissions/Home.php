<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;
use Library\Validate;
use Application\Response;

include_once __DIR__ . './../404.php';

if($myAccount->root == 1){
    // Token and commit create
    $TOKEN_CREATE_NAME = 'name_create_token';
    $COMMIT_CREATE_NAME = 'Name_Create_Commit';

    // Token and commit delete
    $TOKEN_DELETE_NAME = 'name_delete_token';
    $COMMIT_DELETE_NAME = 'Name_Delete_Commit';

    // Token and commit change
    $TOKEN_CHANGE_NAME = 'name_change_token';
    $COMMIT_CHANGE_NAME = 'Name_Change_Commit';

    $post = $request->decode($post);
    include_once 'Permissions/Create.php';
    include_once 'Permissions/Delete.php';
    include_once 'Permissions/Change.php';
    include_once 'Permissions/List.php';

    $strToken = $token->generate(32);
    Session::set($TOKEN_CREATE_NAME, $strToken);
    $tpl->merge($strToken, $TOKEN_CREATE_NAME);
    $tpl->merge($COMMIT_CREATE_NAME, 'name_create_commit');

    $strTokenDel = $token->generate(32);
    Session::set($TOKEN_DELETE_NAME, $strTokenDel);
    $tpl->merge($strTokenDel, $TOKEN_DELETE_NAME);
    $tpl->merge($COMMIT_DELETE_NAME, 'name_delete_commit');

    $strTokenChange = $token->generate(32);
    Session::set($TOKEN_CHANGE_NAME, $strTokenChange);
    $tpl->merge($strTokenChange, $TOKEN_CHANGE_NAME);
    $tpl->merge($COMMIT_CHANGE_NAME, 'name_change_commit');

    $tpl->setFile([
        'content'       => "{$thisModule}/list/index",
        'create'        => "{$thisModule}/list/create",
        'item'          => "{$thisModule}/list/item",
        'sub_script'    => "{$thisModule}/list/script",
        'sub_css'       => "{$thisModule}/list/css",
    ]);

    $tpl->assign([
        'name' => 'Permissions',
        'liclass' => 'active'  
    ], 'breadcrumb');

  	$tpl->merge('active', 'active_permissions');
    $tpl->merge('Manager Permissions', 'site_title');
}