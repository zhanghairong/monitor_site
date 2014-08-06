<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html  xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>monitor</title>

    <base href="<?php echo ConfigManager::$baseurl;?>/" />
    
    <link rel="shortcut icon" href="favicon.ico"/>
    <link type="text/css" rel="stylesheet" href="css/base.css" />
    <link type="text/css" rel="stylesheet" href="css/jquery-ui.css" />
    <link type="text/css" rel="stylesheet" href="css/date_input.css" />
    <link type="text/css" rel="stylesheet" href="css/toastr.css" />
    <script src="js/jquery-1.8.3.js" type="text/javascript"></script>
    <script src="js/jquery-ui-1.9.2.js" type="text/javascript"></script>
    <script src="js/jquery.date_input.js" type="text/javascript"></script>
	<script src="js/ajax.js" type="text/javascript"></script>	
	<script src="js/dump.js" type="text/javascript"></script>	
	<script src="js/date.js" type="text/javascript"></script>
	<script src="js/base.js" type="text/javascript"></script>
	<script src="js/popmanager.js" type="text/javascript"></script>
	<script src="js/jquery.form.js" type="text/javascript"></script>	
	<script src="js/pagemanager.js" type="text/javascript"></script>	
	<script src="js/toastr.js" type="text/javascript"></script>		
	<script src="js/poptipmanager.js" type="text/javascript"></script>
    <script type="text/javascript">
        HttpRequest.basepath = "<?php echo ConfigManager::$baseurl?>";
    </script>
    
</head>

<body>

<div class="layout">
    <div style="text-align: right;padding:10px 20px;">
    <?php if(empty($this->username)){?>
        <a href="javascript:void(0);" onclick="GoLogin();">登录</a>
    <?php }else{?>
        <span ><?php echo $this->username;?></span>
        <a href="javascript:void(0);" onclick="GoLogout();">(退出)</a>
    <?php }?>
    </div>
    <div class="top_nav">
        <?php include ROOT.DS.'views'.DS.'layouts'.DS.'menu.php';?>
    </div>
    <div id="id_main" class="main" style="padding-bottom: 50px;">    
        <div class="content">       
            <?php echo $content;?>
        </div>
    </div>
    
    
    <div class="footer"><span style="display: block;height:20px;">&nbsp;</span></div>
</div>

<script>
function GoLogin()
{
    var url = "Login/Index?backurl="+encodeURIComponent(window.location.href);
    location.href = url;    
    
}

function GoLogout()
{
    var url = "Login/Logout?backurl="+encodeURIComponent(window.location.href);
    location.href = url;   
}
</script>
<div id="mask"  style="display: none;"></div>
<div id="id_popdiv"  style="display: none;" class="popup">    
    <img id="id_pop_loading" src="img/anim_loading_75x75.gif" class="loading" style="display: none;"/>
    <div id="id_pop_content_html">    
    </div>
</div>
<div id="maskiframe" style="display:none;"></div>
<div id="id_popdiviframe" style="display:none;" class="popup">
    <iframe id="id_iframe_pop" src="" frameborder="0" scrolling="no" width="100%" height="100%">
    </iframe>
</div> 
</body> 
<script>

//LoadingManager.Show();
</script>    
</html>