<?php

class MemcacheDataManager
{
    private static $instance = NULL;
    private $objMemcache;
    private $arrValuesTemp;

    public static function getInstance()
    {
        if(!(self::$instance instanceof self))
        {
            self::$instance = new MemcacheDataManager();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->arrValuesTemp = array();
        $this->objMemcache = new CMemcache(ConfigManager::$config['params']['memcache_addrs']);
    }

    public function __destruct()
    {
        $this->updateSessionDataExpire(Session::getSessionId());
    }

    public function setValue($sessionId, $key, $value)
    {
        $memcacheKey = $sessionId .'_'. $key;
        
        $this->arrValuesTemp[$memcacheKey] = $value;
        return true;
    }

    public function getValue($sessionId, $key)
    {
        $memcacheKey = $sessionId .'_'. $key;
        if(array_key_exists($memcacheKey,$this->arrValuesTemp))
        {
            return $this->arrValuesTemp[$memcacheKey];
        }
        $value = $this->objMemcache->GetValue($memcacheKey);
        if(isset($value) && $value!="" && $value!=false)
        {
            $this->arrValuesTemp[$memcacheKey] = $value;
            return $value;
        }
        return false;
    }

    public function delete($sessionId, $key)
    {
        $memcacheKey = $sessionId .'_'. $key;
        if(array_key_exists($memcacheKey,$this->arrValuesTemp))
        {
            unset($this->arrValuesTemp[$memcacheKey]);
        }

        $this->objMemcache->delete($memcacheKey);
    }

    public function getAllValues($sessionId)
    {
        $keyName = $sessionId . '_memcacheKeys';

        $arrRet = $this->arrValuesTemp;
        $arrKeysInSession = $this->objMemcache->GetValue($keyName);
        if(isset($arrKeysInSession) && false != $arrKeysInSession)
        {
            foreach($arrKeysInSession as $memKey)
            {
                if(!array_key_exists($memKey, $this->arrValuesTemp))
                {
                    $value = $this->objMemcache->GetValue($memKey);
                    if(isset($value) && false != $value)
                    {
                        $arrRet[$memKey] = $value;
                    }
                }

            }
        }

        return $arrRet;
    }

    public function cleanAllValues($sessionId)
    {
        $keyName = $sessionId . '_memcacheKeys';
        $arrKeysInSession = $this->objMemcache->GetValue($keyName);
        if(isset($arrKeysInSession) && false != $arrKeysInSession)
        {
            foreach($arrKeysInSession as $memKey)
            {
                $this->objMemcache->delete($memKey);
            }
        }

        $this->arrValuesTemp = array();
    }

    private function updateSessionDataExpire($sessionId)
    {
        $arrayValues = $this->getAllValues($sessionId);
        foreach($arrayValues as $memKey => $value)
        {
            $this->objMemcache->setValue($memKey, $value);
        }

        $keyName = $sessionId . '_memcacheKeys';
        $this->objMemcache->setValue($keyName, array_keys($arrayValues));
    }

}
