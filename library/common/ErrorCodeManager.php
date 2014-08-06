<?php
class ErrorCodeManager
{
    private static $ErrorCodes = array(
        -50050001 => '内部错误',
        -50050002 => '最多只能添加10个应用',
        -50050003 => '添加应用失败',
        -50050004 => '更新应用信息失败',
        -50050005 => '向该应用添加服务失败',
        -50050006 => '添加sas app config失败',
        -50050007 => '非法的serviceid',
        -50050008 => '添加service-app映射失败',
        -50050009 => '非法操作',
        -50050010 => '应用名已存在，不能再添加',
    ); 
    
    public static function GetErrorMessage($code)
    {
        return (isset(self::$ErrorCodes[$code]))?self::$ErrorCodes[$code]:'未知错误';
    }
}