<?php
class CHttpRequestInfo
{	
	
	public static function GetBaseUrl()
	{
		if($secure=self::GetIsSecureConnection())
			$http='https://';
		else
			$http='http://';
		
		return $http . self::GetHostInfo();
	}

    public static function GetFullUrl()
    {
        return self::GetBaseUrl() . self::GetRequestUri();
    }
    
    public static function GetPathInfo()
    {
        if(isset($_SERVER['PATH_INFO']))
        {
            return $_SERVER['PATH_INFO'];
        }
        
        $requesturi = self::GetRequestUri();
        $pos = strpos($requesturi,'?');
        if($pos !== false)
        {
            return substr($requesturi,0,$pos);
        }
        
        return $requesturi;
    }
	
	public static function GetHostInfo()
	{
		$hostInfo = "";
		if(isset($_SERVER['HTTP_HOST']))
		{
			$hostInfo = $_SERVER['HTTP_HOST'];
		}
		else
		{
			$hostInfo = $_SERVER['SERVER_NAME'];
			$port = $secure ? self::GetSecurePort() : self::GetPort();
			if(($port!==80 && !$secure) || ($port!==443 && $secure))
				$hostInfo.=':'.$port;
		}
		
		return $hostInfo;
	}
	
	
	
	public static function GetIsSecureConnection()
	{
		return isset($_SERVER['HTTPS']) && !strcasecmp($_SERVER['HTTPS'],'on');
	}
	
	public static function GetSecurePort()
	{
		$securePort = self::GetIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 443;
		return $securePort;
	}
	
	public static function GetRemoteIp()
	{
		$ip = '';
		if ((isset($_SERVER['HTTP_X_FORWARDED_FOR'])) && (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])))
		{    
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];    
		}
		elseif( (isset($_SERVER['REMOTE_ADDR'])) && (!empty($_SERVER['REMOTE_ADDR']))) 
		{    
			$ip = $_SERVER['REMOTE_ADDR'];    
		}
		return $ip;
	}
	
	public static function GetPort()
	{
		$port = !self::GetIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 80;
		return $port;
	}
	
	public static function GetContentType()
	{
		if(isset($_SERVER['HTTP_ACCEPT']))
		{
			return $_SERVER['HTTP_ACCEPT'];
		}
		else 
		{			
			return "";
		}
	}
	
	public static function GetRequestUri()
	{
		$requestUri = "";
		if(isset($_SERVER['HTTP_X_REWRITE_URL'])) // IIS
		{
			$requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
		}
		else if(isset($_SERVER['REQUEST_URI']))
		{
			$requestUri=$_SERVER['REQUEST_URI'];
			if(isset($_SERVER['HTTP_HOST']))
			{
				if(strpos($requestUri,$_SERVER['HTTP_HOST'])!==false)
					$requestUri=preg_replace('/^\w+:\/\/[^\/]+/','',$requestUri);
			}
			else
				$requestUri=preg_replace('/^(http|https):\/\/[^\/]+/i','',$requestUri);
		}
		else if(isset($_SERVER['ORIG_PATH_INFO']))  // IIS 5.0 CGI
		{
			$requestUri=$_SERVER['ORIG_PATH_INFO'];
			if(!empty($_SERVER['QUERY_STRING']))
				$requestUri.='?'.$_SERVER['QUERY_STRING'];
		}
		else
		{
			$requestUri = "";;
		}
		

		return $requestUri;
	}

    public static function Cookie($key,$default='')
    {
        return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
    }
	
	
	public static function GetJsonData($key,$default=array())
    {
        $data = (isset($_GET[$key])) ? $_GET[$key] : "{}";
		if(get_magic_quotes_gpc()){
			$data = stripslashes($data);
		}
		$arr = json_decode($data,true);
        if(false === $arr || NULL === $arr)
        {
            return $default;
        }
        foreach($arr as $key => $value)
		{
			$arr[$key] = self::FilterUrlInvalidChar($value);
		}
        return $arr;
    }
    public static function PostJsonData($key,$default=array())
    {
        $data = (isset($_POST[$key])) ? $_POST[$key] : "{}";
		if(get_magic_quotes_gpc()){
			$data = stripslashes($data);
		}
		
		$data = iconv(mb_detect_encoding($data, "auto"), "UTF-8", $data);
		$arr = json_decode($data,true);
        if(false === $arr || NULL === $arr)
        {
            return $default;
        }
        foreach($arr as $key => $value)
		{
			$arr[$key] = self::FilterUrlInvalidChar($value);
		}
        return $arr;
    }
	public static function Get($key,$default='')//support array in_params
	{	
		$inPara = (isset($_GET[$key])) ? $_GET[$key] : $default;
        if (is_array($inPara)) {
            return $inPara;
        }
		if(get_magic_quotes_gpc()){
			$inPara = stripslashes($inPara);
		}
		$para = self::FilterUrlInvalidChar($inPara);
        return mb_convert_encoding($para,'utf-8','gb2312,utf-8');
	}

	public static function Post($key,$default='')
	{
		$inPara = (isset($_POST[$key])) ? $_POST[$key] : $default;
        if (is_array($inPara)) {
            return $inPara;
        }
		if(get_magic_quotes_gpc()){
			$inPara = stripslashes($inPara);
		}
		$para = self::FilterUrlInvalidChar($inPara);
        return mb_convert_encoding($para,'utf-8','gb2312,utf-8');
	}
	
	public static function FilterUrlInvalidChar($instr) {
		if(is_array($instr)){
			foreach($instr as $key=>&$value){
				$value = self::FilterUrlInvalidChar($value);
			}
			return $instr;
		}
		$outstr = str_replace(array('(', ')', '||', '--', '/*', '*/'), "", $instr);
		$count = 0;
		while (stripos($outstr, "script") > 0 and $count<10) {
			$outstr = str_ireplace("script", "", $outstr);
			$count++;
		}
		return $outstr;
	}
	
	public static function GetArray(&$inarr)
	{
		foreach($inarr as $key => $value)
		{
			$inarr[$key] = self::Get($key, $value);
		}
	}
	
	public static function PostArray(&$inarr)
	{
		foreach($inarr as $key => $value)
		{
			$inarr[$key] = self::Post($key, $value);
		}
	}
	
	public static function Param($key,$default='')
	{
		$data = self::Post($key,$default);
		if(empty($data)){
			$data = self::Get($key,$default);
		}
		
		return $data;
	}
	public static function ParamsJson($key,$default=array())
	{
		$data = self::PostJsonData($key,$default);
		if(empty($data)){
			$data = self::GetJsonData($key,$default);
		}
		
		return $data;
	}
}

?>