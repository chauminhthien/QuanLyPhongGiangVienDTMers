<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));
use Library\TB;

class Main extends PublicModel{

    function __construct(){
        parent::__construct();
    }

    function countUser(){
        return $this->count(TB::ACCOUNTS, 'id', ['type' => 1]);
    }

    function countCustomer(){
        return $this->count(TB::ACCOUNTS, 'id', ['type' => 0]);
    }

    function countTotalCode($myAccount){
        $condition = "";
        if($myAccount->type == 0) $condition = "created_by = {$myAccount->id}";
        return $this->count(TB::CODE, 'id', $condition);
    }

    function countCheckedCode($myAccount){
        $sql = "SELECT COUNT(`id`) as `numrow` FROM `receive`";

        if($myAccount->type == 0) $sql = "SELECT COUNT(`c`.`id`) as `numrow`
                                          FROM `code` AS `c`, `receive` AS r
                                          WHERE `r`.`code_id` = `c`.`id`";

        $this->query($sql);
        return $this->numRows();
    }

    function countSuccessCode($myAccount){
        $sql = "SELECT COUNT(`r`.`id`) as `numrow` FROM `receive` as `r` WHERE 1";

        if($myAccount->type == 0) $sql = "SELECT COUNT(`c`.`id`) as `numrow`
                                          FROM `code` AS `c`, `receive` AS r
                                          WHERE `r`.`code_id` = `c`.`id`";
        $sql .= " AND `r`.`status` = 3";
        
        $this->query($sql);
        return $this->numRows();
    }
    
}