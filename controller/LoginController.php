<?php
class LoginController extends CBaseController
{
	public $layout='//layouts/main';
    public $mainmenu = '';
    public function actionIndex()
	{
	    $viewarr['backurl'] = CHttpRequestInfo::Get('backurl');
        if(empty($viewarr['backurl']) || 
            false!==strpos(strtolower($viewarr['backurl']),'login/index')){
            $viewarr['backurl'] = "site/index";
        }
        $this->render('index',$viewarr);
	}
    
    public function actionLoginByAccount()
    {
        $account = CHttpRequestInfo::Get("account");
        $passwd = CHttpRequestInfo::Get("password");
        if(empty($account)){
            OutputManager::output(array("code"=>-1,"message"=>"account is empty"),'json');
            return;
        }
        if(false === AccountService::LoginByAccount($account,$passwd)){
            OutputManager::output(array("code"=>-2,"message"=>"no such account"),'json');
            return;
        }
        
        OutputManager::output(array("code"=>0,"message"=>"success"),'json');
    }
    
    public function actionLogout()
    {
        Session::deleteSession();
        $backurl = CHttpRequestInfo::Get('backurl');
        if(empty($backurl) || 
            false!==strpos(strtolower($backurl),'login/index')){
            $backurl = "site/index";
        }
        CHttp::redirect($backurl);
    }
}    