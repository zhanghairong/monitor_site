<?php
class AccountController extends MyController
{
	public function actionIndex()
	{
		$params = array();
		$params["account"] = CHttpRequestInfo::Get("account");


		foreach($params as $key=>$value){
			if(empty($value)){unset($params[$key]);}
		}

        $page = CHttpRequestInfo::Get('page',1);
        if($page<1){$page=1;}
        $pagesize = ConfigManager::$config['params']['pagesize'];
        
        
        $count = AccountService::CountAccount($params);
        $pagenum = ceil($count/$pagesize);
        
		$list = AccountService::SearchAccount($params,($page-1)*$pagesize,$pagesize);
		$this->render('accountlist',array('searchvalues'=>$params,'list'=>$list,'pagenum'=>$pagenum));
	}


	public function actionAddAccountPage()
	{
		$this->renderPartial('addaccount');
	}


	public function actionUpdateAccountPage()
	{
		$id = CHttpRequestInfo::Get('_id');
		$info = AccountService::GetAccountinfoById($id);
		$this->renderPartial('editaccount',array('info'=>$info));
	}


	public function actionAddAccount()
	{
		$params = array();
		$params["account"] = CHttpRequestInfo::Get("account");
		$params["passwd"] = CHttpRequestInfo::Get("passwd");
		$params["privilege"] = CHttpRequestInfo::Get("privilege");

		if(false === AccountService::AddAccount($params))
		{
			OutputManager::output(array('code'=>-1,'message'=>'add account failed'),'json');
			return;
		}
		OutputManager::output(array('code'=>0,'message'=>'success'),'json');
	}


	public function actionUpdateAccount()
	{
		$conditions['_id'] = CHttpRequestInfo::Get('_id');
		if(empty($conditions['_id']))
		{
			OutputManager::output(array('code'=>-11,'message'=>'id is empty'),'json');
			return;
		}
		$params = array();
		$params["account"] = CHttpRequestInfo::Get("account");
		$params["passwd"] = CHttpRequestInfo::Get("passwd");
		$params["privilege"] = CHttpRequestInfo::Get("privilege");


		if(false === AccountService::UpdateAccount($params,$conditions))
		{
			OutputManager::output(array('code'=>-1,'message'=>'update account failed'),'json');
			return;
		}
		OutputManager::output(array('code'=>0,'message'=>'success'),'json');
	}


	public function actionDeleteAccount()
	{
		$conditions['_id'] = CHttpRequestInfo::Get('_id');
		if(empty($conditions['_id']))
		{
			OutputManager::output(array('code'=>-11,'message'=>'id is empty'),'json');
			return;
		}
		if(false === AccountService::DeleteAccount($conditions))
		{
			OutputManager::output(array('code'=>-1,'message'=>'delete userinfo failed'),'json');
			return;
		}
		OutputManager::output(array('code'=>0,'message'=>'success'),'json');
	}


}