<?php
require_once("init.php");

$defaultpath = '/site/index';
$controllerDir = ROOT.DS."controller".DS;

$subpath = "";
$module = (CHttpRequestInfo::GetPathInfo()==''||CHttpRequestInfo::GetPathInfo()=='/')?$defaultpath:CHttpRequestInfo::GetPathInfo();
if(0===substr_compare($module, ".php", strrpos($module,'.php'))){
    $subpath = substr($module,0,strrpos($module,'/')); 
    $module = $defaultpath;
}

$pos = strrpos($module,'/');
if(false===$pos)
{    
    die("Bad Request, please use the right controller");
}
$actionmodule = substr($module,$pos+1);
$moduletmp = substr($module,0,$pos);
$pos2 = strrpos($moduletmp,'/');
if(false===$pos2)
{    
    die("Bad Request, please use the right controller");
}
$controllermodule = substr($moduletmp,$pos2+1);
if(empty($controllermodule) || empty($actionmodule))
{
    die("Bad Request, controller[$controllermodule] or action[$actionmodule] is empty");
}

if(empty($subpath)){$subpath = substr($moduletmp,0,$pos2);}
ConfigManager::$baseurl = CHttpRequestInfo::GetBaseUrl().$subpath;


$controllername = ucfirst($controllermodule)."Controller";
$actionname = "action".ucfirst($actionmodule);

if(false === file_exists("$controllerDir"."$controllername".".php"))
{
    die("Bad Request, cannot find the controller file[$controllerDir"."$controllername".".php]");
}   

require_once("$controllerDir"."$controllername".".php");
if(false === class_exists($controllername))
{
    die("Bad Request, controller[$controllername] not defined");
}

$controller = new $controllername;
if(false === method_exists($controller,$actionname))
{
    die("Bad Request, action[$actionname] not defined");
}

$controller->setControllerModule($controllermodule);
$controller->setActionModule($actionmodule);

$controller->init();

$controller->$actionname();

 
 