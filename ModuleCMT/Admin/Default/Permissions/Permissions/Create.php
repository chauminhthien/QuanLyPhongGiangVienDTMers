<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Session;
use Library\Validate;
use Application\Response;

if (validate_token_commit($post, $TOKEN_CREATE_NAME, $COMMIT_CREATE_NAME)){
        
    $response = ['message' => 'Data invalid.', 'status' => 'error'];

    $rules = [
        'name'       => ['type' => 'string', 'min' => 3, 'max' => 200],
        'key'        => ['type' => 'string', 'min' => 3, 'max' => 200],
        'link'       => ['type' => 'string', 'min' => 0, 'max' => 200],
        'ordering'   => ['type' => 'int', 'min' => 0],
        'parent'     => ['type' => 'int', 'min' => 0]
    ];
    $columns = array_keys($rules);

    $validate = Validate::getInstance($rules, $columns)->setSource($post);
    $data = $validate->run();

    if ($validate->isFullValid()){
        $data = $request->decode($data);

        $response['message'] = 'Disconnect sever.';


        $id = $model->create($data);
        $res = $model->getById($id);
        $res->{"parent_{$res->parent}"} = "selected";
        $tpl->assign($res, 'listPermission');

        $tpl->merge(Session::get($TOKEN_DELETE_NAME), $TOKEN_DELETE_NAME);
        $tpl->merge($COMMIT_DELETE_NAME, 'name_delete_commit');

        $tpl->merge(Session::get($TOKEN_CHANGE_NAME), $TOKEN_CHANGE_NAME);
        $tpl->merge($COMMIT_CHANGE_NAME, 'name_change_commit');

        $parentPers = $model->getPermissionParent(0);
        while($row1 = $model->fetch($parentPers)){
            $tpl->assign($row1, 'parentPers');
            $subPer = $model->getPermissionParent($row1->id);
            while($sub = $model->fetch($subPer)){
                $tpl->assign($sub, 'parentPers.subPers');
            }
        }

        $pathTheme = "{$this->themePath}/{$config->folder}/{$thisModule}/list/item.{$config->extension}";

        $response = [
            'message' => 'Create successfully.', 
            'status' => 'success',
            'action' => 'create',
            'data' => $tpl->setTheme($pathTheme)->getContent()
        ];
    }

    $this->response = $response;
    new Response('Content-Type: application/json', function(){
        return $this->response;
    });
}