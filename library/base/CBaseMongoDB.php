<?php 
class CBaseMongoDb
{
    public $conn;
    public $database;
    
    public function __construct($host,$db)
    {
        $tBegin = Log::getLogTime();
        try{
            $this->conn = new MongoClient($host);  
        } catch (MongoConnectionException $e) {
            $tEnd = Log::getLogTime(); 
            Log::WriteLog('CBaseMongoDb::__construct, '.$e->getMessage(),'error',$tEnd-$tBegin,'dbinfo'); 
            $this->conn = false;  
            return; 
        }
        
        $this->database = $db;
        //$this->conn->selectDB($db);
    }
    
    public function queryByArray($collectionname,$params=array(),$start=0,$num=0)
    {
        if($this->conn == false)
        {
            return array();
        }
        if(isset($params["_id"]))
        {
            $params["_id"] = new MongoId($params["_id"]);
        }
        
        $tBegin = Log::getLogTime();
        $db = $this->conn->selectDB($this->database);  
        $collection = $db->selectCollection($collectionname);      
        $ret = $collection->find($params);
        if($start > 0)
        {
            $ret = $ret->skip($start);
        }
        if($num > 0)
        {
            $ret = $ret->limit($num);
        }
        $ret = iterator_to_array($ret);
        $tEnd = Log::getLogTime(); 
        Log::WriteLog("CBaseMongoDb::queryByArray, collectionname[{$this->database}.{$collectionname}], conditionparams[".
            var_export($params,true)."] ret[".var_export($ret,true)."]", 'info', $tEnd-$tBegin, 'dbinfo');
        
        return $ret;
    }
    public function addArray($collectionname, $params=array())
    {        
        if(empty($params))
        {
            Log::WriteLog("CBaseMongoDb::addArray, collectionname[{$this->database}.{$collectionname}] params cannot be empty.", 'warning', 0, 'dbinfo');
            return false;
        }
        if($this->conn == false)
        {
            return false;
        }
        if(isset($params["_id"]))
        {
            $params["_id"] = new MongoId($params["_id"]);
        }
        $tBegin = Log::getLogTime();
        $db = $this->conn->selectDB($this->database);  
        $collection = $db->selectCollection($collectionname); 
        $ret = $collection->insert($params);
        $tEnd = Log::getLogTime(); 
        Log::WriteLog("CBaseMongoDb::addArray, collectionname[{$this->database}.{$collectionname}] ret[".var_export($ret,true).
            "],  params[".var_export($params,true)."]", 'info', $tEnd-$tBegin, 'dbinfo');
        return @$params["_id"];
    }
    public function updateArray($collectionname,$params=array(), $conditionparams=array(),$options=array("upsert"=>false,"multiple"=>true))
    {        
        if(empty($params)||empty($conditionparams))
        {
            Log::WriteLog("CBaseMongoDb::updateArray, tablename[{$this->database}.{$collectionname}] , params and conditionparams cannot be empty.", 'warning', 0, 'dbinfo');
            return false;
        }
        if(isset($conditionparams["_id"]))
        {
            $conditionparams["_id"] = new MongoId($conditionparams["_id"]);
        }
        $tBegin = Log::getLogTime();
        $db = $this->conn->selectDB($this->database);  
        $collection = $db->selectCollection($collectionname); 
        $ret = $collection->update($conditionparams, array('$set'=>$params),$options);
        $tEnd = Log::getLogTime(); 
        Log::WriteLog("CBaseMongoDb::updateArray, collectionname[{$this->database}.{$collectionname}] ret[".var_export($ret,true)."], conditionparams[".
            var_export($conditionparams,true)."] params[".var_export($params,true)."]", 'info', $tEnd-$tBegin, 'dbinfo');
        return $ret;
    }
    public function deleteByArray($collectionname, $conditionparams=array())
    {        
        if(empty($conditionparams))
        {
            Log::WriteLog("CBaseMongoDb::deleteByArray, tablename[{$this->database}.{$collectionname}] , conditionparams cannot be empty.", 'warning', 0, 'dbinfo');
            return false;
        }
        if(isset($conditionparams["_id"]))
        {
            $conditionparams["_id"] = new MongoId($conditionparams["_id"]);
        }
        $tBegin = Log::getLogTime();
        $db = $this->conn->selectDB($this->database);  
        $collection = $db->selectCollection($collectionname); 
        $ret = $collection->remove($conditionparams);
        $tEnd = Log::getLogTime(); 
        Log::WriteLog("CBaseMongoDb::deleteByArray, collectionname[{$this->database}.{$collectionname}] ret[".var_export($ret,true)."], conditionparams[".
            var_export($conditionparams,true)."]", 'info', $tEnd-$tBegin, 'dbinfo');
        return $ret;
    }
    public function countByArray($collectionname,$params=array())
    {
        if($this->conn == false)
        {
            return;
        }
        foreach($params as $key => &$value)
        {
            if(is_string($value)){
                $value = new MongoRegex('/'.$value.'/');
            }
        }
        
        $tBegin = Log::getLogTime();
        $db = $this->conn->selectDB($this->database);  
        $collection = $db->selectCollection($collectionname);      
        $ret = $collection->find($params)->count();
        $tEnd = Log::getLogTime(); 
        Log::WriteLog("CBaseMongoDb::countByArray, collectionname[{$this->database}.{$collectionname}], conditionparams[".
            var_export($params,true)."] ret[".var_export($ret,true)."]", 'info', $tEnd-$tBegin, 'dbinfo');
        
        return $ret;
    }
    public function searchByArray($collectionname,$params=array(),$start=0,$num=0)
    {
        if($this->conn == false)
        {
            return array();
        }
        foreach($params as $key => &$value)
        {
            if(is_string($value)){
                $value = new MongoRegex('/'.$value.'/');
            }
        }
        $tBegin = Log::getLogTime();
        $db = $this->conn->selectDB($this->database);  
        $collection = $db->selectCollection($collectionname);      
        $ret = $collection->find($params);
        if($start > 0)
        {
            $ret = $ret->skip($start);
        }
        if($num > 0)
        {
            $ret = $ret->limit($num);
        }
        $ret = iterator_to_array($ret);
        $tEnd = Log::getLogTime(); 
        Log::WriteLog("CBaseMongoDb::searchByArray, collectionname[{$this->database}.{$collectionname}], conditionparams[".
            var_export($params,true)."] ret[".var_export($ret,true)."]", 'info', $tEnd-$tBegin, 'dbinfo');
        
        return $ret;
    }
    public function distinct($collectionname, $key, $query) {
        return $this->runCommand(array('distinct'=>$collectionname, 'key'=> $key, 'query'=>$query));
    }
    public function runCommand($params = array()) 
    {
        if($this->conn == false || empty($params))
        {
            return array();
        }

        $tBegin = Log::getLogTime();
        $db = $this->conn->selectDB($this->database);  
        $ret = $db->command($params);     

        $tEnd = Log::getLogTime(); 
        Log::WriteLog("CBaseMongoDb::runCommand, params[".
            var_export($params,true)."] ret[".var_export($ret,true)."]", 'info', $tEnd-$tBegin, 'dbinfo');
        
        return $ret;
    }
}