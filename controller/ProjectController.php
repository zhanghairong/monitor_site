<?php
class ProjectController extends MyController
{
    public function actionIndex()
	{
	    $list = ProjectService::QueryProject();
        $this->render('index',array("list"=>$list));
	}
    public function actionAddProjectPage()
	{
		$this->renderPartial('addproject');
	}
    public function actionUpdateProjectPage()
	{
	    $id = CHttpRequestInfo::Get('projectid');
		$info = ProjectService::GetProjectById($id);
		$this->renderPartial('editproject',array('info'=>$info));
	}    
    public function actionAddProject()
	{
		$params = array();
		$params["name"] = CHttpRequestInfo::Get("name");
		$params["ip"] = CHttpRequestInfo::Get("ip");

		if(false === ProjectService::AddProject($params))
		{
			OutputManager::output(array('code'=>-1,'message'=>'add failed'),'json');
			return;
		}
		OutputManager::output(array('code'=>0,'message'=>'success'),'json');
	}
    public function actionUpdateProject()
	{
		$conditions['_id'] = CHttpRequestInfo::Get('_id');
		if(empty($conditions['_id']))
		{
			OutputManager::output(array('code'=>-11,'message'=>'id is empty'),'json');
			return;
		}
		$params = array();
		$params["name"] = CHttpRequestInfo::Get("name");
		$params["ip"] = CHttpRequestInfo::Get("ip");
		if(false === ProjectService::UpdateProject($params,$conditions))
		{
			OutputManager::output(array('code'=>-1,'message'=>'update failed'),'json');
			return;
		}
		OutputManager::output(array('code'=>0,'message'=>'success'),'json');
	}
    public function actionCurveList()
	{
	    $params["projectid"] = CHttpRequestInfo::Get('projectid');
	    $list = ProjectService::QueryCurve($params);
        $this->renderPartial('curvelist',array("list"=>$list,"projectid"=>$params["projectid"]));
	}
    
    public function actionAddCurvePage()
	{
	    $params["projectid"] = CHttpRequestInfo::Get('projectid');
	    $params["callback"] = CHttpRequestInfo::Get('callback');
        
		$this->renderPartial('addcurve',$params);
	}
    public function actionUpdateCurvePage()
	{
	    $id = CHttpRequestInfo::Get('curveid');
	    $callback = CHttpRequestInfo::Get('callback');
		$info = ProjectService::GetCurveById($id);
		$this->renderPartial('editcurve',array('info'=>$info, 'callback'=>$callback));
	}   
    public function actionAddCurve()
    {
        $params = array();
		$params["name"] = CHttpRequestInfo::Get("name");
		$params["projectid"] = CHttpRequestInfo::Get("projectid");

		if(false === ProjectService::AddCurve($params))
		{
			OutputManager::output(array('code'=>-1,'message'=>'add failed'),'json');
			return;
		}
		OutputManager::output(array('code'=>0,'message'=>'success'),'json');
    }   
    public function actionUpdateCurve()
    {
		$conditions['_id'] = CHttpRequestInfo::Get('_id');
		if(empty($conditions['_id']))
		{
			OutputManager::output(array('code'=>-11,'message'=>'id is empty'),'json');
			return;
		}
        $params = array();
		$params["name"] = CHttpRequestInfo::Get("name");
		//$params["groupid"] = CHttpRequestInfo::Get("groupid");

		if(false === ProjectService::UpdateCurve($params, $conditions))
		{
			OutputManager::output(array('code'=>-1,'message'=>'add failed'),'json');
			return;
		}
		OutputManager::output(array('code'=>0,'message'=>'success'),'json');
    }
    public function actionDeleteCurve()
    {
        $curveid = CHttpRequestInfo::Get('curveid');
        if(false === ProjectService::DeleteCurve(array("_id"=>$curveid)))
		{
			OutputManager::output(array('code'=>-1,'message'=>'delete failed'),'json');
			return;
		}
        OutputManager::output(array('code'=>0,'message'=>'success'),'json');
    }
    
}