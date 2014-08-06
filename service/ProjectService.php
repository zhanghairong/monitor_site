<?php
class ProjectService
{    
    public static function GetProjectById($id)
    {
        $info = ProjectService::QueryProject(array('_id'=>$id));
        if(empty($info))
        {
            return false;
        }
        return $info[$id];
    }
    public static function QueryProject($params=array(),$start=0,$num=0)
    {
        return ProjectDb::GetInstance()->queryProject($params,$start,$num);        
    }
    public static function UpdateProject($params, $conditions)
    {
        return ProjectDb::GetInstance()->updateProject($params,$conditions);  
    }
    public static function AddProject($params)
    {
        return ProjectDb::GetInstance()->addProject($params);  
    }
    public static function DeleteProject($params)
    {
        return ProjectDb::GetInstance()->deleteProject($params);  
    }
    
    public static function GetCurveName($id) 
    {
        $curveinfo = ProjectService::GetCurveById($id);
        return @$curveinfo['name'];
    }
    public static function GetCurveById($id)
    {
        $info = ProjectService::QueryCurve(array('_id'=>$id));
        if(empty($info))
        {
            return false;
        }
        return $info[$id];
    }
    public static function QueryCurve($params=array(),$start=0,$num=0)
    {
        if (isset($params['projectid'])){
            $params['projectid'] = new MongoId($params['projectid']);
        }
        return ProjectDb::GetInstance()->queryCurve($params,$start,$num);        
    }
    public static function UpdateCurve($params, $conditions)
    {
        if (isset($params['projectid'])){
            $params['projectid'] = new MongoId($params['projectid']);
        }
        return ProjectDb::GetInstance()->updateCurve($params,$conditions);  
    }
    public static function AddCurve($params)
    {
        if (isset($params['projectid'])){
            $params['projectid'] = new MongoId($params['projectid']);
        }
        return ProjectDb::GetInstance()->addCurve($params);  
    }
    public static function DeleteCurve($params)
    {
        if (isset($params['projectid'])){
            $params['projectid'] = new MongoId($params['projectid']);
        }
        return ProjectDb::GetInstance()->deleteCurve($params);  
    }
    
}