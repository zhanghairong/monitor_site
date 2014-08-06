<?php
class CGlobalVariableManager
{
	public static function setValue($key, $value)
	{
		$GLOBALS[$key] = $value;
	}
	
	public static function getValue($key)
	{
		if(isset($GLOBALS[$key]))
		{
			return $GLOBALS[$key];
		}
		else
		{
			return "";
		}
	}
}

