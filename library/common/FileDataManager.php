<?php

class FileDataManager
{
    private static $instance = NULL;

    public static function getInstance()
    {
        if(self::$instance == NULL)
        {
            self::$instance = new FileDataManager();
        }
        return self::$instance;
    }

    private function __construct()
    {
        if (!isset($_SESSION)) {
            session_name(Session::$cookiekey);
            session_id(Session::getSessionId());
            session_start();
        }
    }

    public function __destruct()
    {
        //$this->updateSessionDataExpire(Session::getSessionId());
    }

    public function setValue($sessionId, $key, $value)
    {
        $_SESSION[$key] = $value;
        return true;
    }

 
    public function getValue($sessionId, $key)
    {
        return isset($_SESSION[$key])?$_SESSION[$key]:false;
    }

    public function delete($sessionId, $key)
    {
        if(isset($_SESSION[$key]))
        {
            unset($_SESSION[$key]);
        }
    }

    public function cleanAllValues($sessionId)
    {
        session_destroy(); 
        unset( $_SESSION );
    }

}
