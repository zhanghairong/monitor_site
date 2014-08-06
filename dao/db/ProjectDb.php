<?php
class ProjectDb extends CBaseMongoDb
{
	private static $instance = null;

	public static function GetInstance()
	{
		if(self::$instance == null){self::$instance = new ProjectDb();}
		return self::$instance;
	}
	public function __construct()
	{
		parent::__construct(ConfigManager::$config['params']['database']['monitor_config']['host'],
				ConfigManager::$config['params']['database']['monitor_config']['database']);
	}

	public function queryProject($conditions,$start=0,$num=0)
	{
		return $this->queryByArray('project',$conditions,$start,$num);
	}
	public function addProject($params)
	{
		return $this->addArray('project',$params);
	}
	public function updateProject($params,$conditions)
	{
		return $this->updateArray('project',$params,$conditions);
	}
	public function deleteProject($conditions)
	{
		return $this->deleteByArray('project',$conditions);
	}
	public function countProject($conditions)
	{
		return $this->countByArray('project',$conditions);
	}
    public function searchProject($conditions,$start=0,$num=0)
	{
		return $this->searchByArray('project',$conditions,$start,$num);
	}

	public function queryCurve($conditions,$start=0,$num=0)
	{
		return $this->queryByArray('curve',$conditions,$start,$num);
	}
	public function addCurve($params)
	{
		return $this->addArray('curve',$params);
	}
	public function updateCurve($params,$conditions)
	{
		return $this->updateArray('curve',$params,$conditions);
	}
	public function deleteCurve($conditions)
	{
		return $this->deleteByArray('curve',$conditions);
	}
}