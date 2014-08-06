<?php

class CBaseController
{
	public $controllermodule;
	public $actionmodule;
    
    public $layoutdata=array();
    public $layout = "";
    public $sublayout = "";

	public function init(){}

	public function setControllerModule($controllermodule)
	{
		$this->controllermodule = strtolower($controllermodule);
	}
	public function setActionModule($actionmodule)
	{
		$this->actionmodule = $actionmodule;
	}

	public function render($view,$data=null,$return=false,$withlayout=true,$withsublayout=false)
	{
		if(($viewFile=$this->getViewFile($view))!==false)
		{
			$output=$this->renderInternal($viewFile,$data);
		}
		else
		{
			die("cannot find the requested view[$view]");
		}
        
        if($withsublayout && !empty($this->sublayout) && ($layoutFile=$this->getViewFile($this->sublayout))!==false){
            $output=$this->renderInternal($layoutFile,array('content'=>$output));
        }

		if($withlayout && !empty($this->layout) && ($layoutFile=$this->getViewFile($this->layout))!==false){
            $output=$this->renderInternal($layoutFile,array('content'=>$output,'layoutdata'=>$this->layoutdata));
        }
		  
        

		if($return){
		   return $output;
		}else{
		   echo $output;
           return true;
		}
	}

	public function renderPartial($view,$data=null,$return=false)
	{
		return $this->render($view,$data,$return,false);
	}
	
	private function renderInternal($_viewFile_,$_data_=null,$_return_=true)
	{
		if(is_array($_data_))
			extract($_data_,EXTR_PREFIX_SAME,'data');
		else
			$data=$_data_;

		if($_return_)
		{
			ob_start();
			ob_implicit_flush(false);
			require($_viewFile_);
			return ob_get_clean();
		}
		else
		{
			require($_viewFile_);
		}
	}

	private function getViewFile($view)
	{
		if(!isset($view) || $view===false || $view=='')
		  return false;

        if(0 == substr_compare($view, ".php", strpos($view,'.php')))
        {
            $viewfile = $view;
        }
        else if(0 == strncmp($view,'//',2))
        {
            $viewfile = ROOT.DS."views".DS.$view.".php";
        }
        else
        {
            $viewfile = ROOT.DS."views".DS.$this->controllermodule.DS.$view.".php";
        }
		
		if(false === file_exists($viewfile))
		{
			return false;
		}
		return $viewfile;
	}
}
