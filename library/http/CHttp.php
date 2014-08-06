<?php

class CHttp
{
	public static function GetRequest($url, $protocol = 'http', $timeout = 3, $charset ='utf-8')
	{
        $tBegin = Log::getLogTime();
        $ch = curl_init();
        if(false == $ch)
        {
            Log::writeLog('CHttp::GetRequest curl_init failed ,  protocol[' . $protocol . ']  $url[' . $url . ']', 'error');
            return false;
        }

        if ($protocol === 'https')
        {
            $isHttps = true;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        $header = array(
			"MIME-Version: 1.0",
			"Content-type: text/html; charset=" . $charset,
			"Content-transfer-encoding: text"
		);

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_RETURNTRANSFER => 1,
        );
        curl_setopt_array ($ch, $options);

        $content = curl_exec($ch);
        $tEnd = Log::getLogTime();
        //Log::writeLog('httpcode[' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . ']   url[' . $url . ']   ret['.$content.']','info', $tEnd-$tBegin,'interface');

        if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200)
		{
			Log::writeLog('CHttp::GetRequest failed ,  httpcode[', curl_getinfo($ch, CURLINFO_HTTP_CODE) . ']  protocol[' . $protocol . ']  $url[' . $url . ']', 'warning');
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $content;
    }

	public static function PostContent($url, $data, $protocol = 'http', $timeout = 30) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($protocol == 'https') {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);
		
		if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
			curl_close($ch);
			false;
		}

		curl_close($ch);
        return $content;
	}
	
	public static function redirect ($url, $code = 302)
	{
		if ($code == 301) {
			Header('HTTP/1.1 301 Moved Permanently');
		}
		
		header('Location: ' . $url);
        exit();
	}
    public static function RedirectInTop($url)
    {
        $html = '<html><head></head><script type="text/javascript">'.
                'top.location.href="'.$url.'"'.
                '</script><body></body></html>';
                
        echo $html;
        die();
    }
	
	public static function constructGetUrl($baseurl, $arrkeyvalue=array())
	{
		$strUrl = $baseurl . '?';
		$isFirst = true;
		$strUri = self::ConstructGetUriAttrributes($arrkeyvalue);
		return ($strUrl . $strUri);
	}
	
	public static function ConstructGetUriAttrributes($arrkeyvalue=array())
	{
		$strUri = '';
		self::ContructValue($arrkeyvalue, "", $strUri);
		if(!empty($strUri)){
		  $strUri = substr($strUri, 0, strlen($strUri)-1);
		}
		return $strUri;
	}
    public static function ContructValue(&$params, $parentkey, &$out)
    {
        $parentkey = empty($parentkey)?"":$parentkey.'.';
        $str = "";
        foreach($params as $key => &$value)
        {
            if(self::is_vector($value)){
                if(is_array($value[0])){
                    for($i=0; $i<count($value); ++$i){
                        $out .= self::ContructValue($value[$i], $parentkey.$key.'['.$i.']', $out);
                    }
                }else{
                    for($i=0; $i<count($value); ++$i){
                        $parentkey.$key.'='.urlencode($value[$i]).'&';
                    }
                }
            }else if(is_array($value)){
                $out .= self::ContructValue($value, $parentkey.$key, $out);
            }else{
                $out .= $parentkey.$key.'='.urlencode($value).'&';
            }
        }
    }
    
    public static function is_vector( &$array ) {
       if ( !is_array($array) || empty($array) ) {
          return false;
       }
       $next = 0;
       foreach ( $array as $k => &$v ) {
          if ( $k !== $next ) return false;
          $next++;
       }
       return true;
    }
    
    
    public static function RedirectInPost($url, $params)
    {
        $html = '<form action="'.$url.'" method="POST" id="id_hidden_form">';
        
        foreach($params as $key => $value)
        {
            $html .= '<input type="hidden" name="'.$key.'" value=\''.$value.'\'/>';
        }


        $html .= '</form>'.
        $html .= '<script>'.
                'document.getElementById("id_hidden_form").submit();'.
                '</script>';
                
        echo $html;
    }
}
?>