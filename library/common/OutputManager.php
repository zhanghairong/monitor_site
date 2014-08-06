<?php 
class OutputManager
{
	public static function output($msg, $type="text",$return=false)
	{
		$processFail = false;
		$retMsg = "";
		switch ($type)
		{
			case "json":
				if(!is_array($msg))
				{
					$processFail = true;
					break;
				}
				$retMsg = json_encode($msg); 
				break;
				
			case "xml": 				
				if(!is_array($msg))
				{
					$processFail = true;
					break;
				}
				$dom = new DOMDocument("1.0","UTF-8");
				$root = $dom->createElement("xml");
				$dom->appendChild($root);
				if($root)
				{
					self::ConstructXmlMsg($dom, $root, $msg);
				}			
				$retMsg = $dom->saveXML();
				break; 
				
			case "text":
				if(is_array($msg))
				{
					$retMsg = var_export($msg, true);
					break;
				}
				$retMsg = $msg;
				break;
			
			default:
				$retMsg = $msg;
		}
		
		if($processFail)
		{
			$retMsg = "invalid msg format";
		}
        
        if($return)
            return $retMsg;
        else
            echo $retMsg;
	}
	
	public static function ConstructXmlMsg(&$dom, &$parentNode, $arr)
	{
		if(!is_array($arr))
		{
			Log::writeLog('SubAccountsController::ConstructXmlMsg.  invalid $arr(must be array)  $arrType['.gettype($arr).'] $key[' . $key . '] $value[' . var_export($arr,true) . ']', 'error');
			return false;
		}
		foreach($arr as $key => $value)
		{
			$keyName = "";
			if(!is_string($key) || (ord(substr($key,-1))>128))
			{
				Log::writeLog('SubAccountsController::ConstructXmlMsg.  invalid key(must be string and ascii code)  keyType['.gettype($key).'] $key[' . $key . '] $value[' . $value . ']', 'error');
				return false;
			}  			
			
			if(is_array($value))
			{
				$node = $dom->createElement($key);									 
				if($node)
				{					
					if(self::ConstructXmlMsg($dom, $node, $value))
					{
						$parentNode->appendChild($node);
					}					
				}
			}
			else
			{
				$item = $dom->createElement($key, $value);
				if($item)
				{
					$parentNode->appendChild($item);
				}				
			}
		}
		return true;
	}
}