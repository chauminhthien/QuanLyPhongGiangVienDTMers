<?php
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

use Library\TB;
use Library\Session;

class Rooms extends PublicModel{

    final public function __construct(){
        parent::__construct();
    }

    final function filterRoom($date_from, $date_to, $time_from, $time_to, $min_student, $idAcc){
      $where = ($min_student != NULL) ? " `r`.`max_student` >= {$min_student} AND " : "";
      $sql = "SELECT 
                * 
              FROM `rooms` AS `r`
              WHERE
                {$where}
                `r`.`id` NOT IN(SELECT 
                  `res`.`room_id` 
                FROM 
                  `res_rooms` AS `res` 
                WHERE 
                  `res`.`date_from` >= {$date_from} 
                  AND `res`.`date_to` <= {$date_to} 
                  AND `res`.`time_from` >= {$time_from} 
                  AND `res`.`time_to` <= {$time_to}
                  OR `member_id` = {$idAcc}
                  AND `res`.`status` = 1)
              ORDER BY `r`.`max_student` ASC";

      return $this->query($sql);

    }

    final function existRoom(array $data, $idAc = 0){
      $date_from    = $data['date_from'];
      $date_to      = $data['date_to'];
      $time_from    = $data['time_from'];
      $time_to      = $data['time_to'];
      $id           = $data['id'];

      $condition = "
                `date_from` >= {$date_from} 
                  AND `date_to` <= {$date_to} 
                  AND `time_from` >= {$time_from} 
                  AND `time_to` <= {$time_to}
                  AND `room_id` = {$id}
      ";

      if(+$idAc == 0) $condition .= " AND `status` = 1";
      else  $condition .= " AND `member_id` = {$idAc}";

      return $this->countId($condition);
    }

    private function countId($condition){
      $count = $this->count(TB::RES_ROOMS, 'id', $condition);
      return $count > 0;
    }

    function getRoomById($id){
      if(!!$id)
        return $this->getOne(TB::ROOMS, TB::F_ROOMS, ['id' => $id]);
      return null;
    }

    final function createRoom(array $data){
      $result = 0;
      $data['room_id'] = $data['id'];
      unset( $data['id']);

      $data = array_intersect_key($data, array_flip(TB::F_RES_ROOMS));
      if (!empty($data)){
        $result = $this->insert(TB::RES_ROOMS, $data);
      }
      return $result;
    }
}