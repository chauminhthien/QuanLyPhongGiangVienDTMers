<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\URL;
use Library\Paging;

$limit = 10;
$totalItem = $model->countAll();

if($totalItem > 0){
    $page = Paging::current($get, 'page');
    $start = Paging::getStart($limit, $page);
    $skip = ($page - 1) * $limit;

    $end = $start + $limit;
    if ($totalItem < $end) $end = $totalItem;
    $tpl->merge($end, 'paging_item_end');
    $tpl->merge($skip + 1, 'paging_item_start');
    $tpl->merge($totalItem, 'paging_item_total');

    $totalPage = ceil($totalItem / $limit);

    if ($totalPage > 1){
        list($min, $max) = Paging::rangePage($page, 5, $totalPage);

        $pmUrl = [K_URL_DASH, 'permissions'];
        for ($i = $min; $i <= $max; ++$i){
            $pmGet = ($i > 1) ? ['page' => $i] : [];
            $urlI = URL::create($urlSidebar['permissions'], $pmGet);
            
            $btn = ['name' => $i, 'url' => $urlI];
            if ($i == $page) $btn['cur_class'] = 'current';
            $tpl->assign($btn, 'paging_button');
        }

        if ($page < $totalPage){
            $urlNext = URL::create($urlSidebar['permissions'], ['page' => $page + 1]);
            $tpl->merge($urlNext, 'paging_btn_next');
            $urlLast = URL::create($urlSidebar['permissions'], ['page' => $totalPage]);
            $tpl->merge($urlLast, 'paging_btn_last');
        }

        if ($page > 1){
            $pmGet = ($page > 2) ? ['page' => $page - 1] : [];
            $urlPrev = URL::create($urlSidebar['permissions'], $pmGet);
            $tpl->merge($urlPrev, 'paging_btn_prev');
        }

        if ($max < $totalPage) $tpl->box('dotted_right');
        if ($min > 1) $tpl->box('dotted_left');
    }

    $options = [
        'sort' => ['id' => 'ASC'],
        'limit' => $limit,
        'start' => $skip
    ];

    $listPermission = $model->getAll($options);
    while($row = $model->fetch($listPermission)){
        $row->{"parent_{$row->parent}"} = "selected";
        $tpl->assign($row, 'listPermission');
    }

    $parentPers = $model->getPermissionParent(0);
    while($row1 = $model->fetch($parentPers)){
        $tpl->assign($row1, 'parentPers');
        $subPer = $model->getPermissionParent($row1->id);
        while($sub = $model->fetch($subPer)){
            $tpl->assign($sub, 'parentPers.subPers');
        }
    }

}