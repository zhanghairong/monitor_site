<?php

class AccountService
{
    public static function LoginByAccount($account,$password)
    {
        $info = AccountService::QueryAccount(array('account'=>$account,'passwd'=>$password));
        if(empty($info))
        {
            return false;
        }
        $keys = array_keys($info);
        $id = $keys[0];
        Session::SetSessionId($account,$id);
        return $id;
    }
    
    public static function GetUserNameByUserid($userid)
    {
        if(empty($userid))
        {
            return "";
        }
        $info = AccountService::QueryAccount(array('_id'=>$userid));
        if(empty($info))
        {
            return "";
        }
        
        return $info[$userid]['account'];
    }
    
    public static function GetAccountinfoById($id)
    {
        $info = AccountService::QueryAccount(array('_id'=>$id));
        if(empty($info))
        {
            return false;
        }
        return $info[$id];
    }
    
    public static function QueryAccount($params=array(),$start=0,$num=0)
    {
        return AccountDb::GetInstance()->queryAccount($params,$start,$num);        
    }
    public static function UpdateAccount($params, $conditions)
    {
        return AccountDb::GetInstance()->updateAccount($params,$conditions);  
    }
    public static function AddAccount($params)
    {
        return AccountDb::GetInstance()->addAccount($params);  
    }
    public static function DeleteAccount($params)
    {
        return AccountDb::GetInstance()->deleteAccount($params);  
    }
    public static function CountAccount($params=array())
    {
        return AccountDb::GetInstance()->countAccount($params);  
    }
    public static function SearchAccount($params=array(),$start=0,$num=0)
    {
        return AccountDb::GetInstance()->searchAccount($params,$start,$num);  
    }
}
