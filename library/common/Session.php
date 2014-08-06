<?php 
class Session
{
    public static $cookiekey = "PHPOPUSSESSIONID";
    private static $sessionId = '';
    private static $username = '';
    private static $userid = '';
    

    public static function setMemcacheValue($key, $value)
    {
    	return MemcacheDataManager::getInstance()->setValue('', $key, $value);
    }
    public static function getMemcacheValue($key)
    {
    	return MemcacheDataManager::getInstance()->getValue('', $key);
    }
    public static function deleteMemcacheValue($key)
    {
        return MemcacheDataManager::getInstance()->delete('', $key);
    }
    
    
    
    public static function GetSessionTypeClass()
    {
        $sessiontype = 'file';
        if(!empty(ConfigManager::$config["params"]["sessiontype"]) &&
            (ConfigManager::$config["params"]["sessiontype"]=='memcache' ||
            ConfigManager::$config["params"]["sessiontype"] == 'file'))
        {
            $sessiontype = ConfigManager::$config["params"]["sessiontype"];
        };
        return ucfirst($sessiontype).'DataManager';
    }
    
	public static function setValue($key, $value)
    {
        $SessionDataManager = self::GetSessionTypeClass();
        return $SessionDataManager::getInstance()->setValue(self::getSessionId(), $key, $value);
	}	
	public static function getValue($key)
    {
        $SessionDataManager = self::GetSessionTypeClass();
        return $SessionDataManager::getInstance()->getValue(self::getSessionId(), $key);
	}
    public static function deleteValue($key)
    {
        $SessionDataManager = self::GetSessionTypeClass();
        return $SessionDataManager::getInstance()->delete(self::getSessionId(), $key);
    }



    public static function setSessionId($username,$userid)
    {
        self::$username = $username;
        self::$userid = $userid;
        
        self::$sessionId = self::getSessionId();
        if(empty(self::$sessionId )){
            self::$sessionId  = CGuidManager::GetGuid();
            setcookie(self::$cookiekey,self::$sessionId,null,'/');
        }
        
        $SessionDataManager = self::GetSessionTypeClass();
        $SessionDataManager::getInstance()->setValue(self::$sessionId, '',$username);
        $SessionDataManager::getInstance()->setValue(self::$sessionId, 'userid',$userid);
    }

    public static function getSessionId()
    {
        if(empty(self::$sessionId)){
            self::$sessionId = CHttpRequestInfo::Cookie(self::$cookiekey);
        }
        return self::$sessionId;
    }

    public static function getUserName() 
    {
        self::$sessionId = self::getSessionId();
        if(empty(self::$sessionId)){
            return "";
        }
        if(empty(self::$username))
        {
            $SessionDataManager = self::GetSessionTypeClass();
            self::$username = $SessionDataManager::getInstance()->getValue(self::$sessionId, '');
        }
        
        return self::$username;
    }
    public static function GetUserId() 
    {
        self::$sessionId = self::getSessionId();
        if(empty(self::$sessionId)){
            return "";
        }
        
        if(self::$userid == '')
        {
            $SessionDataManager = self::GetSessionTypeClass();
            self::$userid = $SessionDataManager::getInstance()->getValue(self::getSessionId(), 'userid');
        }
        return self::$userid;
    }

    public static function deleteSession()
    {
        setcookie(self::$cookiekey, "", time()-3600, '/');
        $SessionDataManager = self::GetSessionTypeClass();
        $SessionDataManager::getInstance()->cleanAllValues(self::getSessionId());
    }
    
}