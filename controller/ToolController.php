<?php
class ToolController extends CBaseController
{   
    private $controllerpath;
    private $servicepath;
    private $daopath;
    private $viewpath;
    private $db;
    
    public function init()
	{
        $this->controllerpath = ROOT.DS."controller";
        $this->servicepath = ROOT.DS."service";
        $this->daopath = ROOT.DS."dao".DS."db";
        $this->viewpath = ROOT.DS."views";
    
	   header("Content-Type: text/html; charset=utf-8");
       $dbname = CHttpRequestInfo::Get('db');  
       
       $this->db = new CBaseMysqlDb("127.0.0.1","root","root",$dbname);
	}
    public function actionGenerate()
    {
        $dbname = CHttpRequestInfo::Get('db');  
        //$table =  $_GET['table'];  
        if(empty($dbname)) { die("please input the db and table name"); }
        
        
        $this->generateService($dbname);
        $this->generateDao($dbname);
        $this->generateControllerAndViews($dbname);
    }
    public function generateControllerAndViews($dbname)
    {
        $tables = array(
            "tb_userinfo" => array(
                "searchfields"=>array("name","age"),
                "selectfields"=>array(
                    "gender" => array(
                        "showlistcmd"=>'<?php $selectlist=ConfigManager::$config["params"]["tableconfig"]["tb_userinfo"]["gender"];?>',
                        "showkey"=>'$key',
                        "showvalue"=>'<?php echo $value?>'
                    )
                ),
                "skipfields" => array("createtime","idx"),
                "readonlyfields" => array("createtime","idx"),
                "colgroup" => array("5%","20%","20%","10%")
            ),
        );
        
        foreach($tables as $table => $tableinfo)
        {
            $this->generateController($dbname,$table);
            $this->generateView($dbname, $table, $tableinfo);
        }
    }
    
    public function generateController($dbname,$table)
    { 
        $ret = $this->db->select("desc $table;");
        $fields = array();
        
        $filterfields = array("idx","createtime");
        $content = "\t\t\$params = array();\n";
        foreach($ret as $key => $info)
        {
            if(in_array($info["Field"],$filterfields)){continue;}
            $content .= "\t\t".'$params["'.$info["Field"].'"] = CHttpRequestInfo::Get("'.$info["Field"].'");'."\n";
        }
        
        $table = strtolower($table);
        if(0===substr_compare($table,'tb_',0,3)){
            $shorttable = substr($table,3);
        }else if(0===substr_compare($table,'_t',strlen($table)-3,2)){
            $shorttable = substr($table,0,strlen($table)-2);
        }
        
        $shorttable = str_replace("_","",$shorttable);
        
        $listmethod = "\tpublic function actionIndex()\n\t{\n".
                    $content."\n\n" .
                    "\t\tforeach(\$params as \$key=>\$value){\n".
                    "\t\t\tif(empty(\$value)){unset(\$params[\$key]);}\n\t\t}\n\n".
                    "\t\t\$list = ".ucfirst($dbname)."Service::Search".ucfirst($shorttable)."(\$params);\n".
                    "\t\t\$this->render('{$shorttable}list',array('searchvalues'=>\$params,'list'=>\$list));\n".
                    "\t}\n";
        
        $addpage = "\tpublic function actionAdd".ucfirst($shorttable)."Page()\n\t{\n".
                    "\t\t\$this->renderPartial('add{$shorttable}');\n".
                    "\t}\n";
        
        $updatepage = "\tpublic function actionUpdate".ucfirst($shorttable)."Page()\n\t{\n".
                    "\t\t\$id = CHttpRequestInfo::Get('idx');\n" .
                    "\t\t\$info = ".ucfirst($dbname)."Service::Get".ucfirst($shorttable)."ById(\$id);\n".
                    "\t\t\$this->renderPartial('edit{$shorttable}',array('info'=>\$info));\n".
                    "\t}\n";
                    
        $addmethod = "\tpublic function actionAdd".ucfirst($shorttable)."()\n\t{\n".
                    $content."\n" .
                    "\t\t\$params['createtime'] = date('Y-m-d H:i:s');\n\n".
                    "\t\tif(false === ".ucfirst($dbname)."Service::Add".ucfirst($shorttable)."(\$params))\n\t\t{\n".
                    "\t\t\tOutputManager::output(array('code'=>-1,'message'=>'add {$shorttable} failed'),'json');\n".
                    "\t\t\treturn;\n\t\t}\n".
                    "\t\tOutputManager::output(array('code'=>0,'message'=>'success'),'json');\n".
                    "\t}\n";
        
        $updatemethod = "\tpublic function actionUpdate".ucfirst($shorttable)."()\n\t{\n" .
                    "\t\t\$conditions['idx'] = CHttpRequestInfo::Get('idx');\n" .
                    "\t\tif(empty(\$conditions['idx']))\n\t\t{\n" . 
                    "\t\t\tOutputManager::output(array('code'=>-11,'message'=>'id is empty'),'json');\n" . 
                    "\t\t\treturn;\n\t\t}\n" . 
                    $content."\n\n" .
                    "\t\tif(false === ".ucfirst($dbname)."Service::Update".ucfirst($shorttable)."(\$params,\$conditions))\n\t\t{\n".
                    "\t\t\tOutputManager::output(array('code'=>-1,'message'=>'update {$shorttable} failed'),'json');\n".
                    "\t\t\treturn;\n\t\t}\n" .
                    "\t\tOutputManager::output(array('code'=>0,'message'=>'success'),'json');\n" .
                    "\t}\n";
        
        $deletemethod = "\tpublic function actionDelete".ucfirst($shorttable)."()\n\t{\n" .
                    "\t\t\$conditions['idx'] = CHttpRequestInfo::Get('idx');\n" .
                    "\t\tif(empty(\$conditions['idx']))\n\t\t{\n" . 
                    "\t\t\tOutputManager::output(array('code'=>-11,'message'=>'id is empty'),'json');\n" . 
                    "\t\t\treturn;\n\t\t}\n" . 
                    "\t\tif(false === ".ucfirst($dbname)."Service::Delete".ucfirst($shorttable)."(\$conditions))\n\t\t{\n".
                    "\t\t\tOutputManager::output(array('code'=>-1,'message'=>'delete {$shorttable} failed'),'json');\n".
                    "\t\t\treturn;\n\t\t}\n" .
                    "\t\tOutputManager::output(array('code'=>0,'message'=>'success'),'json');\n" .
                    "\t}\n";
                    
        $php  =  "<?php\n".
                 "class ".ucfirst($shorttable)."Controller extends MyController\n{\n".           
                 "{$listmethod}\n\n{$addpage}\n\n{$updatepage}\n\n{$addmethod}\n\n{$updatemethod}\n\n{$deletemethod}\n\n".
                  "}";
        $newfile = $this->controllerpath.DS.ucfirst($shorttable)."Controller.php";
        file_put_contents($newfile,$php,FILE_USE_INCLUDE_PATH);
        echo "generate $newfile\n<p/>\n"; 
    }
    public function generateView($dbname,$table,$tableinfo)
    {        
        $ret = $this->db->select("desc $table;");
        $fields = array();
        foreach($ret as $key => $info)
        {
            $fields[] = $info["Field"];
        }
        
        $arr["fields"] = $fields;
        $arr["dbname"] = $dbname;
        $arr["table"] = strtolower($table);
        
        if(0===substr_compare($arr["table"],'tb_',0,3)){
            $arr["shorttable"] = substr($arr["table"],3);
        }else if(0===substr_compare($arr["table"],'_t',strlen($arr["table"])-3,2)){
            $arr["shorttable"] = substr($arr["table"],0,strlen($arr["table"])-2);
        }
        
        $arr["skipfields"] = array("idx","createtime","update_time");
        
        $arr = array_merge($arr,$tableinfo);
        
        $this->generateAddView($arr);
        
        $arr["skipfields"] = array("createtime");
        $arr["readonlyfields"] = array("idx");
        
        
        $this->generateEditView($arr);
        $this->generateListView($arr);
    }
    public function generateListView($arr)
    {
        $file = ROOT.DS."template".DS."list.php";
        $content =  $this->renderPartial($file,$arr,true);
        $newfile = $this->viewpath.DS.$arr["shorttable"].DS.$arr["shorttable"]."list.php";
        file_put_contents($newfile,$content,FILE_USE_INCLUDE_PATH);
        echo "generate $newfile\n<p/>\n";
    }
    public function generateAddView($arr)
    {
        $file = ROOT.DS."template".DS."add.php";
        
        $content =  $this->renderPartial($file,$arr,true);
        $newfile = $this->viewpath.DS.$arr["shorttable"].DS."add".$arr["shorttable"].".php";
        file_put_contents($newfile,$content,FILE_USE_INCLUDE_PATH);
        echo "generate $newfile\n<p/>\n";
    }
    public function generateEditView($arr)
    {
        $file = ROOT.DS."template".DS."edit.php";
        
        $content =  $this->renderPartial($file,$arr,true);
        $newfile = $this->viewpath.DS.$arr["shorttable"].DS."edit".$arr["shorttable"].".php";
        file_put_contents($newfile,$content,FILE_USE_INCLUDE_PATH);
        echo "generate $newfile\n<p/>\n";
    }
    
    
    public function generateDao($database)
    {
        $classname = ucwords($database)."Db";

        $phpcontent = "<?php\nclass $classname extends CBaseMysqlDb\n{\n".
                "\tprivate static \$instance = null;\n\n".
                "\tpublic static function GetInstance()\n\t{\n".
                "\t\tif(self::\$instance == null){self::\$instance = new {$classname}();}\n".
                "\t\treturn self::\$instance;\n\t}\n".
                
                "\tpublic function __construct()\n\t{\n".
                "\t\tparent::__construct(ConfigManager::\$config['params']['database']['$database']['host'],\n".
                "\t\t\t\tConfigManager::\$config['params']['database']['$database']['user'],\n".
                "\t\t\t\tConfigManager::\$config['params']['database']['$database']['password'],\n".
                "\t\t\t\tConfigManager::\$config['params']['database']['$database']['database']);\n\t}\n\n";
                
        
        $ret = $this->db->select("show tables;");   
    	foreach($ret as $key => $info)
    	{
    	    $tablename = $info["Tables_in_{$database}"];
    	    $nameinmethod = $tablename;
        	if(0 === strncmp($nameinmethod,'tb_',3))
        	{
        		$nameinmethod = substr($nameinmethod,3);
        	}    	
        	$nameinmethod = ucwords($nameinmethod);
            
            
    		$phpcontent .= "\tpublic function query{$nameinmethod}(\$conditions)\n\t{\n".
                    "\t\treturn \$this->queryByArray('{$tablename}',\$conditions);\n\t}\n".
                    
                    "\tpublic function add{$nameinmethod}(\$params)\n\t{\n".
                    "\t\treturn \$this->addArray('{$tablename}',\$params);\n\t}\n".
                    
                    "\tpublic function update{$nameinmethod}(\$params,\$conditions)\n\t{\n".
                    "\t\treturn \$this->updateArray('{$tablename}',\$params,\$conditions);\n\t}\n".
                    
                    "\tpublic function delete{$nameinmethod}(\$conditions)\n\t{\n".
                    "\t\treturn \$this->deleteByArray('{$tablename}',\$conditions);\n\t}\n".
                    
                    "\tpublic function count{$nameinmethod}(\$conditions)\n\t{\n".
                    "\t\treturn \$this->countByArray('{$tablename}',\$conditions);\n\t}\n".
                    
                    "\tpublic function search{$nameinmethod}(\$conditions)\n\t{\n".
                    "\t\treturn \$this->queryLikeByArray('{$tablename}',\$conditions);\n\t}\n\n";
    	}
    	$phpcontent  .= "}";
        
        $newfile = $this->daopath.DS."{$classname}.php";
    	file_put_contents ($newfile, $phpcontent,FILE_USE_INCLUDE_PATH);
        echo "generate $newfile\n<p/>\n";
    }
    
    public function generateService($database)
    {
        $classname = ucwords($database)."Service";

        $phpcontent = "<?php\nclass $classname\n{\n";
        
        $ret = $this->db->select("show tables;");   
    	foreach($ret as $key => $info)
    	{
    	    $tablename = $info["Tables_in_{$database}"];
    	    $nameinmethod = $tablename;
        	if(0 === strncmp($nameinmethod,'tb_',3))
        	{
        		$nameinmethod = substr($nameinmethod,3);
        	}    	
        	$nameinmethod = ucwords($nameinmethod);
            
            $daoclassname = ucwords($database)."Db";
            
            $phpcontent .= "\tpublic static function Get{$nameinmethod}ById(\$id)\n\t{\n".
                        "\t\t\$info = self::Query{$nameinmethod}(array('idx'=>\$id));\n".
                        "\t\treturn empty(\$info)?array():\$info[0];\n\t}\n".
                        
                        "\tpublic static function Query{$nameinmethod}(\$conditions=array())\n\t{\n".
                        "\t\treturn {$daoclassname}::GetInstance()->query{$nameinmethod}(\$conditions);\n\t}\n".
                        
                        "\tpublic static function Add{$nameinmethod}(\$params)\n\t{\n".
                        "\t\treturn {$daoclassname}::GetInstance()->add{$nameinmethod}(\$params);\n\t}\n".
                        
                        "\tpublic static function Update{$nameinmethod}(\$params,\$conditions)\n\t{\n".
                        "\t\treturn {$daoclassname}::GetInstance()->update{$nameinmethod}(\$params,\$conditions);\n\t}\n".
                        
                        "\tpublic static function Delete{$nameinmethod}(\$conditions=array())\n\t{\n".
                        "\t\treturn {$daoclassname}::GetInstance()->delete{$nameinmethod}(\$conditions);\n\t}\n".
                        
                        "\tpublic static function Count{$nameinmethod}(\$conditions=array())\n\t{\n".
                        "\t\treturn {$daoclassname}::GetInstance()->count{$nameinmethod}(\$conditions);\n\t}\n".
                        
                        "\tpublic static function Search{$nameinmethod}(\$conditions=array())\n\t{\n".
                        "\t\treturn {$daoclassname}::GetInstance()->search{$nameinmethod}(\$conditions);\n\t}\n\n";
    	}
    	$phpcontent  .= "}";
        
        $newfile = $this->servicepath.DS."{$classname}.php";
    	file_put_contents ($newfile, $phpcontent,FILE_USE_INCLUDE_PATH);
        echo "generate $newfile\n<p/>\n";
    }
}