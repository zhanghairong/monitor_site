<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html  xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	
    <title>simple</title>

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
</head>

<body>

    <?php echo $content; ?>


<div id="mask" style="display:none;"></div>
<div id="id_popdiv"  style="display: none;" class="popup">    
    <img id="id_pop_loading" src="/img/anim_loading_75x75.gif" class="loading" style="display: none;"/>
    <div id="id_pop_content_html"></div>
</div>
<div id="maskiframe" style="display:none;"></div>
<div id="id_popdiviframe" style="display:none;" class="popupiframe">
    <iframe id="id_iframe_pop" src="" frameborder="0" scrolling="no" width="100%" height="100%">
    </iframe>
</div>  
</body>
        
</html>