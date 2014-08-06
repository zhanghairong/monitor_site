<?php

class Log{
	public static function WriteLog($message,$type='info',$time=0, $category='all')
	{
	    $config = ConfigManager::GetConfig();
        
		if(!isset($config['params']['logconfigs'][$category]))
		{
			return false;
		}
		$arrCategory = $config['params']['logconfigs'][$category];
		$arrLevels = $arrCategory['levels'];
		$timeLine = $arrCategory['timeLevel'];
		
		if(!isset($arrLevels) || !isset($timeLine) || !is_array($arrLevels))
		{
			return false;
		}
		if(!array_key_exists($type,$arrLevels) && (($time==0) || ((double)$time < $timeLine)))
		{
			return true;
		}
		
		$randLevel = rand(1,100);
		if($randLevel > $arrLevels[$type])
		{
			return true;
		}
		
		$dir = $arrCategory['dir'];		
		$dirMonth = $dir . DS . date("Y.m");
		if((!file_exists($dirMonth)) || (!is_dir($dirMonth))) 
		{
			mkdir($dirMonth, 0775, true);
		}
		
		$filename = $dirMonth . DS . $arrCategory['filename'] . '.' . date('Y.m.d');	
		
		$timeInterval = "";
		if((double)$time != 0)
		{
			$timeInterval = 'timeInterval[' . (double)$time . 'ms] ';				
		}
		
		$remoteIp = 'remoteIp[' . CHttpRequestInfo::getRemoteIp() . ']';

        $username = 'username['.Session::getUserName().']';

        $sessionid = 'sessionid['.Session::getSessionId().']';
		$msg = str_replace(chr(10), '',$message);
        $msg = str_replace(chr(13), '',$msg);
		$msg = '[' . strtoupper($type) . '] [' . date ("Y-m-d H:i:s") . '] ' . $remoteIp . ' ' . $timeInterval.$sessionid .$username.' ' . $msg   . "\r\n\r\n";
		
		for($i = 0; $i<3; $i++)
		{
			if (file_put_contents ( $filename, $msg, FILE_APPEND ) > 0)
			{
				return true;
			}
		}			
		
		return false;
	}
    public static function GetLogTime()
    {
		list($usec, $sec) = explode(" ", microtime());  			
		return number_format(((float)$usec + (float)$sec)*1000,0,'.','');
	}
}
