<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\URL;

include_once  __DIR__ . "./../401.php";

if($next){

// Token and commit delete
    $TOKEN_DELETE_NAME = 'name_delete_member_token';
    $COMMIT_DELETE_NAME = 'Name_Delete_Member_Commit';

    // Token and commit change
    $TOKEN_CHANGE_NAME = 'name_change_member_token';
    $COMMIT_CHANGE_NAME = 'Name_Change_Member_Commit';

    $post = $request->decode($post);
    include_once 'Members/List.php';

    $strTokenDel = $token->generate(32);
    Session::set($TOKEN_DELETE_NAME, $strTokenDel);
    $tpl->merge($strTokenDel, $TOKEN_DELETE_NAME);
    $tpl->merge($COMMIT_DELETE_NAME, 'name_delete_member_commit');

    $strTokenChange = $token->generate(32);
    Session::set($TOKEN_CHANGE_NAME, $strTokenChange);
    $tpl->merge($strTokenChange, $TOKEN_CHANGE_NAME);
    $tpl->merge($COMMIT_CHANGE_NAME, 'name_change_member_commit');

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

    $tpl->merge('Members manager', 'site_title');
}
