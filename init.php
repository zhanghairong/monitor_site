<?php

define( 'DS', DIRECTORY_SEPARATOR );
define( 'ROOT', dirname( __FILE__ )) ;
define( 'HISTORYDATADIR', ROOT.DS.'data'.DS.'historydata'.DS) ;
define( 'REALTIMEDATADIR', ROOT.DS.'data'.DS.'realtimedata'.DS) ;
define( 'STRUCTHISTORYDIR', ROOT.DS.'data'.DS.'structhistory'.DS) ;

require_once(ROOT.DS."config".DS."main.php");

function requiredir($dir)
{
	if ($handle = opendir($dir)) 
	{
		while(false !== ($file = readdir($handle))) 
		{
            if(0===substr_compare($file, ".php", strrpos($file,'.php')))
			{
				require_once($dir.DS.$file);
			}
		}
		closedir($handle);
	}
}

requiredir(ROOT.DS."library".DS."common");
requiredir(ROOT.DS."library".DS."base");
requiredir(ROOT.DS."library".DS."http");
requiredir(ROOT.DS."library".DS."phpmailer");
requiredir(ROOT.DS."dao".DS."interface");;
requiredir(ROOT.DS."dao".DS."db");
requiredir(ROOT.DS."service");
requiredir(ROOT.DS."library");



