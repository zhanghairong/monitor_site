<?php
class CBaseOracleDb
{
    public $conn = false;
    public $sqls = array();
    public $multiTransaction = false;
    public $schema;
    
    
    public function __construct($host,$user,$pwd)
    {
        if(0 != strncmp("oci:dbname=",$host,strlen($host))){
            $host = "oci:dbname=".$host;
        }
        $tBegin = Log::getLogTime();
		try {
			$this->conn = new PDO($host, $user, $pwd);  
		} catch (PDOException $e) {
			$tEnd = Log::getLogTime();    
			Log::WriteLog('CBaseOracleDb::__construct, new pdo failed, error['.$e->getMessage().']','error',$tEnd-$tBegin,'dbinfo');
		}

        $this->schema = $user;
    }
    
    
    public function constructSqlConditions($params=array())
    {
        $conditions = array();
        foreach($params as $key => $value)
        {
            if(is_array($value)){
                if(!empty($value)){
                    $conditions[] = "$key in ('".implode("', '", $value)."') ";
                }
            }
            else
            {
                $conditions[] = " $key='$value' ";
            }
        }
        
        if(!empty($conditions)){
            return implode(' and ', $conditions);
        }
        
        return "";
    }
    
    public function queryByArray($tablename,$params=array(),$ex="")
    {        
        $conditionstr = $this->constructSqlConditions($params);
        if(!empty($conditionstr)){
            $conditionstr = "where ".$conditionstr;
        }
        
        $sql = "select * from {$this->schema}.$tablename $conditionstr $ex";
        
        return $this->select($sql);
    }
    public function countByArray($tablename,$params=array())
    {
        $conditionstr = $this->constructSqlConditions($params);
        if(!empty($conditionstr)){
            $conditionstr = "where ".$conditionstr;
        }
        
        $sql = "select count(*) from {$this->schema}.$tablename $conditionstr";
        
        return $this->select($sql);
    }
    public function queryLikeByArray($tablename,$params=array(),$ex="")
    {
        $conditions = array();
        foreach($params as $key => $value)
        {
            if(is_array($value))
            {
                $subconditions = array();
                foreach($value as $key2=>$value2)
                {
                    $subconditions[] = " $key like '%$value2%' ";
                }
                if(!empty($subconditions)){
                    $conditions[] = "(".implode(" or ", $subconditions).") ";
                }
            }
            else
            {
                $conditions[] = " $key like '%$value%' ";
            }
            
        }
        
        $conditionstr = "";
        if(!empty($conditions)){
            $conditionstr = "where " . implode(' and ', $conditions);
        }
        
        $sql = "select * from {$this->schema}.$tablename $conditionstr $ex";
        return $this->select($sql);
    }
    public function addArray($tablename, $params=array())
    {        
        if(empty($params))
        {
            Log::WriteLog("CBaseOracleDb::addArray, tablename[{$this->schema}.$tablename] $params cannot be empty.", 'warning');
            return false;
        }
        
        $fields = "";
        $values = "";
        foreach($params as $key => $value)
        {
            $fields .= ($fields==''?'':',') . $key;
            $values .= ($values==''?'':',') . "'$value'";
        }
        
        $sql = "insert into {$this->schema}.$tablename ($fields) values($values)";
        
        return $this->insert($sql);
    }
    
    public function updateArray($tablename,$params=array(), $conditionparams=array())
    {        
        if(empty($params)||empty($conditionparams))
        {
            Log::WriteLog("CBaseOracleDb::updateArray, tablename[{$this->schema}.$tablename] , $params or $conditionparams cannot be empty.", 'warning');
            return false;
        }
        
        $updatevalues = '';
        foreach($params as $key => $value)
        {
            $updatevalues .= ($updatevalues==''?'':',') . "$key='$value'";
        }

        $conditionstr = $this->constructSqlConditions($conditionparams);
        if(!empty($conditionstr)){
            $conditionstr = "where ".$conditionstr;
        }
        
        $sql = "update {$this->schema}.$tablename set $updatevalues $conditionstr";
        return $this->update($sql);
    }
    public function deleteByArray($tablename, $conditionparams=array())
    {
        $conditionstr = $this->constructSqlConditions($conditionparams);        
        if(empty($conditionstr)){ //forbidden
            Log::WriteLog("CBaseOracleDb::deleteByArray, tablename[{$this->schema}.$tablename] , attemp to delete data without conditions, $conditionparams cannot be empty.", 'warning');
            return false;
        }
        
        $conditionstr = "where ".$conditionstr;
        
        $sql = "delete from {$this->schema}.$tablename $conditionstr";
        
        return $this->execute($sql);
    }
    
    
    
    
    public function select($sql)
    {        
        return $this->query($sql);
    }
    
    public function insert($sqls,$returninsertid=true)
    {        
        if(false === $this->execute($sqls))
        {
            return false;
        }
        return true;
    }
    
    public function delete($sqls)
    {    
        return $this->execute($sqls);        
    }
    
    public function update($sqls)
    {
        return $this->execute($sqls);       
    }
    
    private function query($sql)
    {
        if($this->conn === false)
        {
            Log::WriteLog("schema[{$this->schema}]   sql[".$sql.'], error[database haven\'t be connected]','error', 0, 'dbinfo');
            return array();
        }
        
        $tBegin = Log::getLogTime();
		
        $stmt = $this->conn->prepare($sql);
        if($stmt === false)
        {
            $errormsg = $this->conn->errorInfo();
            Log::WriteLog("schema[{$this->schema}]  parse sql[{$sql}], error[".var_export($errormsg,true).']','error', 0, 'dbinfo');
			return array();
        }
        
        $ret = $stmt->execute();
        if(false === $ret) {
            $errormsg = $stmt->errorInfo ();
            Log::WriteLog("schema[{$this->schema}]   execute failed, sql[{$sql}] failed, error[".var_export($errormsg,true).']','error', 0, 'dbinfo');
            return array();
        }
		
        $tEnd = Log::getLogTime();        

		$retarr =  $this->resource2array($stmt);
		Log::WriteLog("schema[{$this->schema}]   sql[$sql]    ret[" . var_export($retarr,true) . ']', 'info', $tEnd-$tBegin, 'dbinfo');
        
        return $retarr;  
    }
    
    public function beginMultiTransaction()
    {
        $this->multiTransaction = true;
    }
    public function endMultiTransaction()
    {
        return $this->executetransaction($this->sqls);
    }
    private function execute($sqls)
    {        
        if($this->conn === false)
        {
            Log::WriteLog("schema[{$this->schema}]   sql[".var_export($sql,true).'], error[database haven\'t be connected]','error', 0, 'dbinfo');
            return false;
        }
        
        $sqlarr = is_array($sqls) ? $sqls : array($sqls);
        
        if($this->multiTransaction){
            $this->sqls = array_merge($this->sqls,$sqlarr);
            return;
        }
        
           
        return $this->executetransaction($sqlarr);
    }
    
    private function executetransaction($sqls)
    {        
        if($this->conn === false)
        {
            Log::WriteLog("schema[{$this->schema}]   sql[".$sql.'], error[database haven\'t be connected]','error', 0, 'dbinfo');
            return array();
        }
        
        $this->multiTransaction = false;
        
        try 
        {        
            $tBegin = Log::getLogTime();
    		$this->conn->beginTransaction();
    		
            foreach($sqls as $key => $sql)
            {
    			$stmt = $this->conn->prepare($sql);
    			if($stmt === false)
    			{
    				$errormsg = $this->conn->errorInfo();
    				Log::WriteLog("schema[{$this->schema}]  parse sql[{$sql}], error[".var_export($errormsg,true).']','error', 0, 'dbinfo');
    				return false;
    			}
    			
    			$ret = $stmt->execute();
    			if(false === $ret) {
    				$errormsg = $stmt->errorInfo ();
    				Log::WriteLog("schema[{$this->schema}]   execute failed, sql[{$sql}] failed, error[".var_export($errormsg,true).']','error', 0, 'dbinfo');
    				return false;
    			}
            }
    		
            $committed = $this->conn->commit();
            if(false === $committed) {
                $errormsg = $this->conn->errorInfo();
                Log::WriteLog("schema[{$this->schema}]   oci_commit failed, sqls[".var_export($sqls,true).'] failed, error['.var_export($errormsg,true).']','error', $tEnd-$tBegin, 'dbinfo');
                $this->conn->rollBack();
                return false;
            }  
            
            $tEnd = Log::getLogTime();
            Log::WriteLog("schema[{$this->schema}]   sqls[".var_export($sqls,true)."]    ret[" . var_export($committed,true) . ']', 'info', $tEnd-$tBegin, 'dbinfo');
                 
            return true;   
          
		} 
        catch (PDOException $e) {
			Log::WriteLog("schema[{$this->schema}]   execute failed, error[".$e->getMessage().']','error',0,'dbinfo');
            $this->conn->rollBack();
            return false;
		}            
    }
    
    
    private function resource2array($resource)
    {
		if($resource===false)
		{
			return array();
		}
		$retarr = array();
		while($row = $resource->fetch(PDO::FETCH_ASSOC))
		{
			$retarr[] = $row;
		}
		
		return $retarr;
    }
}