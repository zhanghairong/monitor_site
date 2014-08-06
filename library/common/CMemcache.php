<?php
class CMemcache
{
    private $memcache;
    private $connStatus;

	public function __construct($memcache_addrs) 
	{        
        $this->connStatus = false;
        
        if(!isset($memcache_addrs))
        {
            Log::writeLog('CMemcache::__construct memcache_addrs is not configed','error');
        }
        else
        {
           $this->memcache = new Memcache();
    		foreach($memcache_addrs as $addr)
            {
                if(isset($addr['ip']) && isset($addr['port']))
                {
                    if(true == $this->memcache->addserver($addr['ip'], $addr['port'],false))
                    {
                        if(0 != $this->memcache->getServerStatus($addr['ip'], $addr['port']))
                        {
                            $this->connStatus = true;
                        }
                        else
                        {
                            Log::writeLog('CMemcache::__construct connect server['.$addr['ip'].':'.$addr['port'].']fail','error');
                        }
                    }
                }
                else
                {
                    Log::writeLog('CMemcache::__construct memcache_server or memcache_port configes error','error');
                }
            } 
        }
	}
	
	public function __destruct()
	{
        if(false != $this->connStatus)
        {
            $this->memcache->close();
            $this->connStatus = false;
        }
	}
	
	public function SetValue($key,$value,$expire=1800)
	{
        if(!isset($key) ||  $key=='')
        {
            Log::writeLog('CMemcache::SetValue.  $key is null', 'warning');
            return false;
        }
		if(false == $this->connStatus)
		{
			Log::writeLog('CMemcache::SetValue memcache_server is not useable now','error');
			return false;
		}

        $ret = $this->memcache->set( $key, $value, 0, $expire);
        //Log::writeLog("CMemcache::SetValue ret[$ret]  [$key]=[$value]",'debug');
        return $ret;
	}
	
	public function GetValue($key, $defaultValue="")
	{
        if(!isset($key) ||  $key=='')
        {
            Log::writeLog('CMemcache::GetValue.  $key is null', 'warning');
            return false;
        }
        if(false == $this->connStatus)
        {
            Log::writeLog('CMemcache::GetValue memcache_server is not useable now','error');
            return $defaultValue;
        }

        $ret = $this->memcache->get( $key);

        //Log::writeLog("CMemcache::GetValue memcache_server ",'debug');

        return (false != $ret) ? $ret : false;
	}
	
	public function Delete($key)
	{
        if(!isset($key) ||  $key=='')
        {
            Log::writeLog('CMemcache::Delete.  $key is null', 'warning');
            return false;
        }

        if(false == $this->connStatus)
        {
            Log::writeLog('CMemcache::Delete memcache_server is not useable now','error');
            return false;
        }

        Log::writeLog("CMemcache::Delete memcache_server [$key]",'debug');
        return $this->memcache->delete( $key);
	}
	
	public function FlushValues()
	{
        if(false == $this->connStatus)
        {
            Log::writeLog('CMemcache::FlushValues memcache_server is not useable now','error');
            return false;
        }

        return $this->memcache->flush();
	}
}
