<?php
class AccountDb extends CBaseMongoDb
{
	private static $instance = null;

	public static function GetInstance()
	{
		if(self::$instance == null){self::$instance = new AccountDb();}
		return self::$instance;
	}
	public function __construct()
	{
		parent::__construct(ConfigManager::$config['params']['database']['monitor_config']['host'],
				ConfigManager::$config['params']['database']['monitor_config']['database']);
	}

	public function queryAccount($conditions,$start=0,$num=0)
	{
		return $this->queryByArray('account',$conditions,$start,$num);
	}
	public function addAccount($params)
	{
		return $this->addArray('account',$params);
	}
	public function updateAccount($params,$conditions)
	{
		return $this->updateArray('account',$params,$conditions);
	}
	public function deleteAccount($conditions)
	{
		return $this->deleteByArray('account',$conditions);
	}
	public function countAccount($conditions)
	{
		return $this->countByArray('account',$conditions);
	}
    public function searchAccount($conditions,$start=0,$num=0)
	{
		return $this->searchByArray('account',$conditions,$start,$num);
	}
}