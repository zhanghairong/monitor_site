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
    public function actionActionCurvePage()
    {
        $viewarr['projectid'] = CHttpRequestInfo::Get('projectid');
        $viewarr['curveid'] = CHttpRequestInfo::Get('curveid');
        $viewarr['day'] = CHttpRequestInfo::Get('day', date("Y-m-d"));
        $viewarr['curvename'] = ProjectService::GetCurveName($viewarr['curveid']);
        $timestamp = strtotime($viewarr['day']);
        $viewarr['ips'] = CurveService::QueryDistinctIp($viewarr['projectid'], $viewarr['curveid'], $timestamp);

        $this->renderPartial('actioncurve',$viewarr);
    }
    public function actionDateCurvePage()
    {
        $viewarr['projectid'] = CHttpRequestInfo::Get('projectid');
        $viewarr['curveid'] = CHttpRequestInfo::Get('curveid');
        $viewarr['curvename'] = ProjectService::GetCurveName($viewarr['curveid']);
        $viewarr['day1'] = date("Y-m-d");
        $timestamp = strtotime($viewarr['day1']);
        $viewarr['ips'] = CurveService::QueryDistinctIp($viewarr['projectid'], $viewarr['curveid'], $timestamp);
        $viewarr['pages'] = CurveService::QueryDistinctPage($viewarr['projectid'], $viewarr['curveid'], $timestamp);

        $viewarr['day2'] = date("Y-m-d",strtotime("-1 day"));
        $this->renderPartial('datecurve',$viewarr);
    }
    public function actionGetLineData()
    {
        $projectid = CHttpRequestInfo::Get('projectid');
        $curveid = CHttpRequestInfo::Get('curveid');
        $day = CHttpRequestInfo::Get('day',Date("Y-m-d"));
        $ip = explode(',', CHttpRequestInfo::Get('ips'));
        $groupbyip = CHttpRequestInfo::Get('groupbyip', 1);
        if (empty($ip) || empty($ip[0])) {
            OutputManager::output(array('code'=>-1,'message'=>'please choose ip'), 'json');
            return -1;
        }
        $page = CHttpRequestInfo::Get('page');
        
        
        $timestamp = strtotime($day);
        $interval = 5 * 60;
        
        $ret = CurveService::GetActionData($projectid, $curveid, $timestamp, $ip, $groupbyip, $page);
        OutputManager::output(array('code'=>0,'message'=>'success','data'=>
                array_merge($ret, array('interval'=>$interval, 'begintime'=>$timestamp))),'json');
        //$this->renderPartial('curvepage');
    }
    public function actionDailyReport()
    {
        $viewarr['projectid'] = CHttpRequestInfo::Get('projectid');
        $viewarr['curveid'] = CHttpRequestInfo::Get('curveid');
        $viewarr['day'] = CHttpRequestInfo::Get('day', date("Y-m-d"));
        $viewarr['curvename'] = ProjectService::GetCurveName($viewarr['curveid']);
        $timestamp = strtotime($viewarr['day']);
        $viewarr['ips'] = CurveService::QueryDistinctIp($viewarr['projectid'], $viewarr['curveid'], $timestamp);

        $this->renderPartial('dailyreport',$viewarr);
    }
    public function actionGetDailyReportData()
    {
        $projectid = CHttpRequestInfo::Get('projectid');
        $curveid = CHttpRequestInfo::Get('curveid');
        $day = CHttpRequestInfo::Get('day',Date("Y-m-d"));
        $ip = explode(',', CHttpRequestInfo::Get('ips'));
        $groupbyip = CHttpRequestInfo::Get('groupbyip', 1);
        if (empty($ip) || empty($ip[0])) {
            OutputManager::output(array('code'=>-1,'message'=>'please choose ip'), 'json');
            return -1;
        }
        
        $timestamp = strtotime($day);
        $interval = 5 * 60;
        
        $viewarr['lines'] = CurveService::QueryDailyData($projectid, $curveid, $timestamp, $ip, $groupbyip);
        $this->renderPartial('reportdata',$viewarr);
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