<?php
class CurveDb extends CBaseMongoDb
{
	private static $instance = null;

	public static function GetInstance()
	{
		if(self::$instance == null){self::$instance = new CurveDb();}
		return self::$instance;
	}
	public function __construct()
	{
		parent::__construct(ConfigManager::$config['params']['database']['monitor_action']['host'],
				ConfigManager::$config['params']['database']['monitor_action']['database']);
	}

	public function queryAction($projectid, $timestamp, $conditions,$start=0,$num=0)
	{
	    $this->database = ConfigManager::$config['params']['database']['monitor_action']['database'].'_'.date("Y_m", $timestamp);
        $collection = "action_" . $projectid . '_' . date("Y_m_d",$timestamp);
		return $this->queryByArray($collection,$conditions,$start,$num);
	}
}