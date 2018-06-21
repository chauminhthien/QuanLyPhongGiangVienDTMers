<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

$tpl->setFile([
    'content' => "{$thisModule}/index",
    'top'     => "{$thisModule}/top",
    'bottom'  => "{$thisModule}/bottom",
    'sub_css'  => "{$thisModule}/css",
]);
