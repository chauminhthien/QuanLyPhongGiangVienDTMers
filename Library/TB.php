<?php
namespace Library;
defined('K_ROOT') || die(pathinfo(__FILE__, PATHINFO_FILENAME));

class TB{

    const ACCOUNTS      = 'accounts';
    const F_ACCOUNTS    = [
        'id', 'fullname', 'email', 'password', 'type', 'permission_id', 'token',
        'status', 'avatar', 'gender', 'created_at', 'created_by', 'updated_at', 'updated_by', 'root'
    ];

    const MEMBERS      = 'members';
    const F_MEMBERS    = [
        'id', 'email', 'password', 'avatar', 'fullname', 'gender', 'phone',
        'birthday', 'token', 'status', 'created_at', 'created_by'
    ];

    const PERMISSIONS      = 'permissions';
    const F_PERMISSIONS    = ['id', 'name', 'link', 'parent', 'ordering', 'key'];

    const SETTING   = 'settings';
    const F_SETTING = ['id', 'name', 'extra', 'type'];

    const ROOMS   = 'rooms';
    const F_ROOMS = ['id', 'name', 'max_student', 'status'];

    const RES_ROOMS   = 'res_rooms';
    const F_RES_ROOMS = [
        'id', 'date_from', 'date_to', 'time_from', 'time_to', 'member_id', 'created_at',
        'updated_at', 'update_by', 'room_id', 'status', 'seen'];

}