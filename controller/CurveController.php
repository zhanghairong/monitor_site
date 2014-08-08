<?php
class CurveController extends MyController
{
    public function actionIndex()
	{
        $projectid = CHttpRequestInfo::Get('projectid');
	    $projectlist = ProjectService::QueryProject();
        $projectids = array_keys($projectlist);
        if (empty($projectid) || !in_array($projectid, $projectids)){
            $projectid = $projectids[0];
        }
        $projectinfo = $projectlist[$projectid];//ProjectService::GetProjectById($projectid);
        $curvelist = ProjectService::QueryCurve(array("projectid"=>$projectid));
        
        $this->render('index',array('projectinfo'=>$projectinfo,'curvelist'=>$curvelist));
	}
    public function actionChooseProject() {
        $projectid = CHttpRequestInfo::Get('projectid');
	    $projectlist = ProjectService::QueryProject();
        $this->renderPartial('chooseproject',array('projectlist'=>$projectlist,'currentprojectid'=>$projectid));
    }
    public function actionLoadCurvePage()
    {
        $viewarr['projectid'] = CHttpRequestInfo::Get('projectid');
        $viewarr['curveid'] = CHttpRequestInfo::Get('curveid');
        $viewarr['date'] = CHttpRequestInfo::Get('date', date("Y-m-d"));
        $viewarr['curvename'] = ProjectService::GetCurveName($viewarr['curveid']);
        $this->renderPartial('curvepage',$viewarr);
    }
    public function actionGetLineData()
    {
        $projectid = CHttpRequestInfo::Get('projectid');
        $curveid = CHttpRequestInfo::Get('curveid');
        $day = CHttpRequestInfo::Get('day',Date("Y-m-d"));
        $timestamp = strtotime($day);
        $interval = 5 * 60;
        
        $ret = CurveService::GetActionData($projectid, $curveid, $timestamp);
        OutputManager::output(array('code'=>0,'message'=>'success','data'=>
                array_merge($ret, array('interval'=>$interval, 'begintime'=>$timestamp))),'json');
        //$this->renderPartial('curvepage');
    }
    public function actionLoadLineTest()
    {
        $begin = strtotime(date("Y-m-d 00:00:00"));
        $interval = 5 * 60;
        $lines = array();
        for($i=0; $i<200; ++$i){
            $random = rand(20,50);
            $lines['line1'][$begin + $interval * $i] = $random;
            //$lines['line2'][$begin + $interval * $i] = $random + 10;
        }
        $data = array('lines'=>$lines,'interval'=>$interval, 'begintime'=>$begin);
        OutputManager::output(array('code'=>0,'message'=>'success','data'=>$data),'json');
    }
}    