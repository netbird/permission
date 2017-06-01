<?php

namespace Fuyuan\Tools;

/**
 * Created by PhpStorm.
 * User: fuyuan1
 * Date: 17/6/1
 * Time: 下午3:42
 */
class Permission
{
    /**
     * 十六进制字符串转换成2进制字符串
     *
     * @param string $hel_str
     * @return string 二进制字符串
     */
    private function hexToBin($hex_str)
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
     * 二进制转换成16进制字符串
     *
     * @param $bin_str 二进制字符串
     * @return string 十六进制字符串
     */
    private function binToHex($bin_str)
    {
        if (empty($bin_str)) {
            return '';
        }
        $i = 0;
        $result = '';
        $tmp_str = '';
        while (strlen($tmp_str = substr($bin_str, $i, 4)) > 0) {
            $i += 4;
            if (strlen($tmp_str) < 4) {
                for ($j = strlen($tmp_str); $j < 4; $j ++) {
                    $tmp_str .= '0';
                }
            }
            $result .= base_convert($tmp_str, 2, 16);
        }
        return $result;
    }
    /**
     * 设置目标权限
     *
     * @param $per_v 占用位 为数字或者array()
     *            为数字 设置单个位置
     *            为array() 设置多个位置
     *
     * @param $perm 现有的标示字段
     *
     * @return $perm 现有的权限值
     */
    public function setuserPermission($permission_index, $hex_perm)
    {

        if (empty($hex_perm)) {
            $hex_perm = '';
        }
        $bin_perm = $this->hexToBin($hex_perm);

        if (is_array($permission_index)) {
            foreach ($permission_index as $v) {
                $perm_len = strlen($bin_perm);
                if ($perm_len < $v) {
                    for ($i = $perm_len + 1; $i < $v; $i ++) {
                        $bin_perm .= '0';
                    }
                    $bin_perm .= '1';
                } else {
                    $bin_perm = substr($bin_perm, 0, $v - 1) . '1' . substr($bin_perm, $v);

                }
            }
        } else {
            $perm_len = strlen($bin_perm);
            if ($perm_len < $permission_index) {
                for ($i = $perm_len + 1; $i < $permission_index; $i ++) {
                    $bin_perm .= '0';
                }
                $bin_perm .= '1';
            } else {
                $bin_perm = substr($bin_perm, 0, $permission_index - 1) . '1' . substr($bin_perm, $permission_index);

            }
        }
        return $this->binToHex($bin_perm);
    }

    /**
     * 删除制定的权限
     *
     * @param unknown_type $per_v 占位
     *
     * @param unknown_type $perm
     *            用户现有的权限内容 16进制
     */
    public function deleteUserPermission($permission_index, $user_perm)
    {
        if (empty($user_perm)) {
            $user_perm = '';
            return;
        }
        $bin_perm = $this->hexToBin($user_perm);

        if ($bin_perm >= $permission_index) {
            $bin_perm = substr($bin_perm, 0, $permission_index - 1) . '0' . substr($bin_perm, $permission_index);
        }

        return rtrim($this->binToHex($bin_perm), '0');

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
        if (substr($bin_perm, $permission_index - 1, 1)) {
            return true;
        } else {
            return false;
        }
    }

}