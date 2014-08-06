<?php 
class CBaseMysqlDb
{
    public $conn;
    public $database;
    public $sqls = array();
    public $multiTransaction = false;
    
    
    public function __construct($host,$user,$pwd,$db,$charset="utf8")
    {
        $tBegin = Log::getLogTime();
        $this->conn = @mysql_connect($host,$user,$pwd);
        $tEnd = Log::getLogTime();                        
        if($this->conn === false)
        {
            Log::WriteLog('CBaseMysqlDb::__construct, mysql_connect failed, host['.$host.']  errorno['.mysql_errno().'], error message['.mysql_error().']','error',$tEnd-$tBegin,'dbinfo');
            return;
        }
        
        mysql_query("SET NAMES '$charset'",$this->conn);
        $this->database = $db;
        mysql_select_db($db, $this->conn);
    }
    
    public function constructSqlConditions($params=array(),$table="t")
    {
        $conditions = array();
        foreach($params as $key => $value)
        {
            if($key===0 && is_array($value)){  //直接输入的条件
                foreach($value as $k=>$con){
                    $conditions[] = $con;
                }
            }else if(is_array($value)){  //自己拼的条件
                if(!empty($value)){
                    $conditions[] = "$table.$key in ('".implode("', '", $value)."') ";
                }
            }
            else
            {
                $conditions[] = " $table.$key='$value' ";
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
        
        $sql = "select * from {$this->database}.$tablename t $conditionstr $ex";
        
        return $this->select($sql);
    }
    public function countByArray($tablename,$params=array())
    {
        $conditionstr = $this->constructSqlConditions($params);
        if(!empty($conditionstr)){
            $conditionstr = "where ".$conditionstr;
        }
        
        $sql = "select count(*) as count from {$this->database}.$tablename $conditionstr";
        
        $ret = $this->select($sql);
        return $ret[0]['count'];
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
                    $subconditions[] = " t.$key like '%$value2%' ";
                }
                if(!empty($subconditions)){
                    $conditions[] = "(".implode(" or ", $subconditions).") ";
                }
            }
            else
            {
                $conditions[] = " t.$key like '%$value%' ";
            }
            
        }
        
        $conditionstr = "";
        if(!empty($conditions)){
            $conditionstr = "where " . implode(' and ', $conditions);
        }
        
        $sql = "select * from {$this->database}.$tablename t $conditionstr $ex";
        return $this->select($sql);
    }
    public function addArray($tablename, $params=array())
    {        
        if(empty($params))
        {
            Log::WriteLog("CBaseMysqlDb::addArray, tablename[{$this->database}.$tablename] $params cannot be empty.", 'warning');
            return false;
        }
        
        $fields = "";        
        $valuestr = "";
        if(isset($params[0]))
        {
            foreach($params[0] as $field => $value)
            {
                $fields .= ($fields==''?'':',') . "{$this->database}.$tablename.".$field;
            }
            
            $valueitems = array();
            foreach($params as $key => $item)
            {
                $values = "";
                foreach($item as $field => $value)
                {
                    $values .= ($values==''?'':',') . "'$value'";
                }
                $valueitems[] = "({$values})";                
            }
            $valuestr = implode(",",$valueitems);
        }
        else
        {
            $values = "";
            foreach($params as $field => $value)
            {
                $fields .= ($fields==''?'':',') . "{$this->database}.$tablename.".$field;
                $values .= ($values==''?'':',') . "'$value'";
            }
            $valuestr = "({$values})";
        }
        
        
        $sql = "insert into {$this->database}.$tablename ($fields) values $valuestr ;";
        return $this->insert($sql);
    }
    
    public function updateArray($tablename,$params=array(), $conditionparams=array())
    {        
        if(empty($params)||empty($conditionparams))
        {
            Log::WriteLog("CBaseMysqlDb::updateArray, tablename[{$this->database}.$tablename] , params and conditionparams cannot be empty.", 'warning');
            return false;
        }
        
        $updatevalues = '';
        foreach($params as $key => $value)
        {
            $updatevalues .= ($updatevalues==''?'':',') . "t.$key='$value'";
        }

        $conditionstr = $this->constructSqlConditions($conditionparams);
        if(!empty($conditionstr)){
            $conditionstr = "where ".$conditionstr;
        }
        
        $sql = "update {$this->database}.$tablename t set $updatevalues $conditionstr";
        return $this->update($sql);
    }
    public function deleteByArray($tablename, $conditionparams=array())
    {
        $conditionstr = $this->constructSqlConditions($conditionparams,"{$this->database}.$tablename");        
        if(empty($conditionstr)){ //forbidden
            Log::WriteLog("CBaseMysqlDb::deleteByArray, tablename[{$this->database}.$tablename] , attemp to delete data without conditions, $conditionparams cannot be empty.", 'warning');
            return false;
        }
        
        $conditionstr = "where ".$conditionstr;
        
        $sql = "delete from {$this->database}.$tablename $conditionstr";
        
        return $this->execute($sql);
    }
    
    public function select($sql)
    {        
        return $this->query($sql);
        //return $this->resource2array($ret);  
    }
    
    public function insert($sqls,$returninsertid=true)
    {        
        if(false === $this->execute($sqls))
        {
            return false;
        }
        
        if($returninsertid)
        {
            $resid = mysql_query("SELECT LAST_INSERT_ID();", $this->conn);
            if($id = mysql_fetch_row($resid))
            {
                return $id[0];
            }
        }
        
        return true;
        //return mysql_insert_id($this->conn);
    }
    
    public function delete($sqls)
    {    
        return $this->execute($sqls);        
    }
    
    public function update($sqls)
    {
        return $this->execute($sqls);       
    }
    
    public function query($sql,$printlog=true)
    {
        if($this->conn === false)
        {
            Log::WriteLog("database[{$this->database}]   sql[".$sql.'], error[database haven\'t be connected]','error', 0, 'dbinfo');
            return array();
        }
        
        $tBegin = Log::getLogTime();
        $ret = mysql_query($sql, $this->conn);
        $tEnd = Log::getLogTime();
        if($ret === false)
        {
            Log::WriteLog("database[{$this->database}]   sql[".$sql.'], errorno['.mysql_errno().'], error message['.mysql_error().']','error', $tEnd-$tBegin, 'dbinfo');
			return array();
        }
		
		$retarr =  $this->resource2array($ret);
        if($printlog){
            Log::WriteLog("database[{$this->database}]   sql[$sql]    ret[" . var_export($retarr,true) . ']', 'info', $tEnd-$tBegin, 'dbinfo');
        }
		
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
    public function execute($sqls)
    {        
        if($this->conn === false)
        {
            Log::WriteLog("database[{$this->database}]   sql[".var_export($sqls,true).'], error[database haven\'t be connected]','error', 0, 'dbinfo');
            return false;
        }
        
        $sqlarr = is_array($sqls) ? $sqls : array($sqls);
        
        if($this->multiTransaction){
            $this->sqls = array_merge($this->sqls,$sqlarr);
            return;
        }
        
           
        return $this->executetransaction($sqlarr);
    }
    
    public function executetransaction($sqls,$printlog=true)
    {
        $tBegin = Log::getLogTime();
        mysql_query("START TRANSACTION", $this->conn);
        $ret = true;
        foreach($sqls as $key => $sql)
        {
            $ret = mysql_query($sql, $this->conn);            
            if($ret === false)
            {
                break;
            }
        }
        $tEnd = Log::getLogTime();
        
        if($ret === false)
        {
            mysql_query("ROLLBACK", $this->conn);
            mysql_query("END", $this->conn);
            if($printlog){
                Log::WriteLog('execute sql['.$sql.'] failed, will rollback, errorno['.mysql_errno().'], error message['.mysql_error().'], rollback sqls['.var_export($sqls,true).']','error', $tEnd-$tBegin, 'dbinfo');
            }
            return false;
        }
        
        $ret = mysql_query("COMMIT", $this->conn);        
        mysql_query("END", $this->conn);   
        if(false === $ret)
        {
            
            if($printlog){
                Log::WriteLog('commit transaction failed, sql['.var_export($sqls,true).'], errorno['.mysql_errno().'], error message['.mysql_error().']','error', $tEnd-$tBegin, 'dbinfo');
            }
            return false;
        }        
        
        
        if($printlog){
            Log::WriteLog("database[{$this->database}]   sqls[".var_export($sqls,true)."]    ret[" . var_export($ret,true) . ']', 'info', $tEnd-$tBegin, 'dbinfo');
        }
        return true;       
    }
    
    
    public function resource2array($resource)
    {
        if((!is_resource($resource) || get_resource_type($resource) != 'mysql result') || (0 == mysql_num_rows($resource)))
        {
            return array();
        }
        
        $retarr = array();
        while($row = mysql_fetch_array($resource,MYSQL_ASSOC))
    	{
    	    $retarr[] = $row;
    	}
        
        return $retarr;
    }
}