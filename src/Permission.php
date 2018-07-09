<?php

namespace Tools;

/**
 * Created by PhpStorm.
 * User: fuyuan1
 * Date: 17/6/1
 * Time: 下午3:42
 */
class Permission
{
    /**
     * 十六进制字符串转换成2进制字符串, 因为后期还会用到二进制，所以没有对二进制字符串，做左侧去0处理
     * 例如：FE 转成 1111 1110
     *
     * @param string $hel_str 目标16进制字符串
     * @return string 二进制字符串
     */
    public static function hexToBin($hex_str)
    {
        if (empty($hex_str)) {
            return '';
        }
        $return_str = '';
        $count = strlen($hex_str);
        for ($i = 0; $i < $count; $i ++) {
            $tmp_str = base_convert($hex_str[$i], 16, 2);
            $return_str .= sprintf("%04s", $tmp_str);
        }
        return $return_str;
    }

    /**
     * 二进制转换成16进制字符串(与正常的二进制数字不同)
     * 从右边开始 每4位进行转化
     *
     * @param $bin_str 二进制字符串
     * @return string 十六进制字符串
     */
    public static function binToHex($bin_str)
    {
        if (empty($bin_str)) {
            return '';
        }

        $i = 0;
        $result = '';
        $bin_str = strrev($bin_str);
        while ($tmp_str = substr($bin_str, $i, 4)) {
            $result .= base_convert(strrev($tmp_str), 2, 16);
            $i += 4;
        }

        return strrev($result);
    }
    /**
     * 设置目标权限
     *
     * @param $permission_index 占用位 为数字或者array()
     *            为数字 设置单个位置
     *            为array() 设置多个位置
     *
     * @param $user_perm 用户的权限值
     *
     * @return string 设置之后的权限值
     */
    public static function setuserPermission($permission_index, $user_perm)
    {

        if (empty($user_perm)) {
            $user_perm = '';
        }
        $bin_perm = self::hexToBin($user_perm);

        if (is_array($permission_index)) {
            foreach ($permission_index as $v) {
                $perm_len = strlen($bin_perm);
                if ($perm_len < $v) {
                    $bin_perm = '1' . str_repeat('0', $v - $perm_len - 1) . $bin_perm;
                } else {
                    $bin_perm = substr($bin_perm, 0, $perm_len - $v) . '1' . substr($bin_perm, ($perm_len - $v + 1));
                }
            }
        } else {
            $perm_len = strlen($bin_perm);
            if ($perm_len < $permission_index) {
                $bin_perm = '1' . str_repeat('0', $permission_index - $perm_len - 1) . $bin_perm;
            } else {
                $bin_perm = substr($bin_perm, 0, $perm_len - $permission_index) . '1' . substr($bin_perm, ($perm_len - $permission_index + 1));
            }
        }
        return self::binToHex($bin_perm);
    }

    /**
     * 删除制定的权限
     *
     * @param unknown_type $permission_index 目标权限站位
     *
     * @param unknown_type $user_perm
     *            用户现有的权限内容 16进制
     */
    public static function deleteUserPermission($permission_index, $user_perm)
    {
        if (empty($user_perm)) {
            return '';
        }
        $bin_perm = self::hexToBin($user_perm);
        $perm_len = strlen($bin_perm);

        if ($perm_len >= $permission_index) {
            $bin_perm = substr($bin_perm, 0, $perm_len - $permission_index) . '0' . substr($bin_perm, $perm_len - $permission_index + 1);
        }

        return ltrim(self::binToHex($bin_perm), '0');

    }

    /**
     * 根据权限判定是否符合条件
     *
     * @param unknown_type $per_v 需要判断的占有位 从1开始的一个十进制数字
     * @param unknown_type $user_perm 所在用户权限值例如ABC，DEF等
     */
    public static function isAllowUserPermission($permission_index, $user_perm)
    {

        $bin_perm = self::hexToBin($user_perm);
        if (strlen($bin_perm) < $permission_index) {
            return false;
        }
        if (substr($bin_perm, strlen($bin_perm) - $permission_index, 1)) {
            return true;
        } else {
            return false;
        }
    }

}