<?php
class MyController extends CBaseController
{
	public $layout='//layouts/main';
	public $mainmenu;
    public $username="";

	public function init()
    {
        //header("Content-Type: text/xml;text/html");
        parent::init();
        Log::writeLog('request['.CHttpRequestInfo::GetFullUrl().']','debug');
        $this->mainmenu = $this->controllermodule;

        $userid = Session::GetUserId();
        $this->username = AccountService::GetUserNameByUserid($userid);
	}
}