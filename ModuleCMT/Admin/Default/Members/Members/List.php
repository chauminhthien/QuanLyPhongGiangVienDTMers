<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\Paging;
use Library\URL;

$limit = 10;
$totalItem = $model->countAllMembers();

if ($totalItem > 0){
    $totalPage = ceil($totalItem / $limit);
    
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

        $pmUrl = $urlSidebar['members'];

        for ($i = $min; $i <= $max; ++$i){
            $pmGet = ($i > 1) ? ['page' => $i] : [];
            $urlI = URL::create($urlSidebar['members'], $pmGet);
            
            $btn = ['name' => $i, 'url' => $urlI];
            if ($i == $page) $btn['cur_class'] = 'current';
            $tpl->assign($btn, 'paging_button');
        }

        if ($page < $totalPage){
            $urlNext = URL::create($urlSidebar['members'], ['page' => $page + 1]);
            $tpl->merge($urlNext, 'paging_btn_next');
            $urlLast = URL::create($urlSidebar['members'], ['page' => $totalPage]);
            $tpl->merge($urlLast, 'paging_btn_last');
        }

        if ($page > 1){
            $pmGet = ($page > 2) ? ['page' => $page - 1] : [];
            $urlPrev = URL::create($urlSidebar['members'], $pmGet);
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

    $listMembers = $model->getAllMember($options);
    while($row = $model->fetch($listMembers)){
        $row->url_view = URL::create([K_URL_DASH, 'members', 'edit', $row->id]);
        $row->checked = ($row->status) ? 'checked' : '';
        $row->time = date('d-m-Y', $row->created_at);
        $tpl->assign($row, 'listMembers');
    }
    
}