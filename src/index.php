<?php
/**
 * Created by PhpStorm.
 * User: fuyuan1
 * Date: 2018/7/8
 * Time: 下午8:35
 */
include "Permission.php";

use \Tools\Permission as Permission;

$hex_str = '12FEC1';
$target = Permission::hexToBin($hex_str);
var_dump('hexToBin: ' . $hex_str . ' bin: ' . $target );


$bin_str = '1010101001';
$target = Permission::binToHex($bin_str);
var_dump('binToHex: ' . $bin_str . ' hex: ' . $target );

////////////////////////////////////////////////////////////////
$user_permission = '';
$target_permission = [12, 13];
$target2_permission = 8;
$new_user_permission = Permission::setuserPermission($target_permission, $user_permission);
var_dump($new_user_permission . ' Bin: ' . Permission::hexToBin($new_user_permission));

$new_user_permission = Permission::setuserPermission($target2_permission, $new_user_permission);
var_dump($new_user_permission . ' Bin: ' . Permission::hexToBin($new_user_permission));

////////////////////////////////////////////////////////////////

$have_permission_8 = Permission::isAllowUserPermission(8, $new_user_permission);
var_dump('have_permission_8: ' . $have_permission_8);

$have_permission_14 = Permission::isAllowUserPermission(14, $new_user_permission);
var_dump('have_permission_14: ' . $have_permission_14);


$have_permission_6 = Permission::isAllowUserPermission(6, $new_user_permission);
var_dump('have_permission_6: ' . $have_permission_6);

//////////////////////////////////////////////////////////////
$new_user_permission = Permission::deleteUserPermission(8, $new_user_permission);
var_dump('delete permission 8 ... ');
$have_permission_8 = Permission::isAllowUserPermission(8, $new_user_permission);
var_dump('have_permission_8: ' . $have_permission_8);

$have_permission_12 = Permission::isAllowUserPermission(12, $new_user_permission);
var_dump('have_permission_12: ' . $have_permission_12);

var_dump($new_user_permission . ' Bin: ' . Permission::hexToBin($new_user_permission));