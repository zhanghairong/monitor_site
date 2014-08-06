<link type="text/css" rel="stylesheet" href="<?php echo ConfigManager::$baseurl; ?>/css/base.css" />
<script src="<?php echo ConfigManager::$baseurl; ?>/js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="<?php echo ConfigManager::$baseurl; ?>/js/ajax.js" type="text/javascript"></script>	
<script src="<?php echo ConfigManager::$baseurl; ?>/js/dump.js" type="text/javascript"></script>	

<script type="text/javascript">
$(document).ready(function(){ 
	$("li.fold").toggle(
	   function(){ $(this).parent().addClass("unfold");},
	   function(){ $(this).parent().removeClass("unfold");}	   
	   );
	$("li.unfold a.first").toggle(
	   function(){ $(this).parent().removeClass("unfold");$(this).parent().addClass("fold");},
	   function(){ $(this).parent().addClass("unfold");}
	   )
}) 
</script>
<div class="main main_server" >
<div class="content">
	<!--sidebar-->
    <div class="sidebar">
        <ul class="menu" id="id_ul_list"> 	
			<li class="unfold">
            	<a href="#"  class="first">服务介绍(消费者)</a>
                <span class="arrow"></span>
                <ul class="sub">
				    <li class="cur">
                    	<a href="javascript:void(0);" onclick="showRightContent(this,'<?php echo ConfigManager::$baseurl; ?>/sc/introduce')">服务简介</a>
                		<span class="arrow"></span>
                    </li>
                </ul>
            </li>
        	<li class="unfold">
            	<a href="#"  class="first">查看服务列表(消费者)</a>
                <span class="arrow"></span>
                <ul class="sub">
				    <li>
                    	<a href="javascript:void(0);" onclick="showRightContent(this,'<?php echo ConfigManager::$baseurl; ?>/sc/ListByServiceid')">按服务名查看</a>
                		<span class="arrow"></span>
                    </li>
                	<li>
                    	<a href="javascript:void(0);" onclick="showRightContent(this,'<?php echo ConfigManager::$baseurl; ?>/sc/ListByDomain');">按域查看</a>
                		<span class="arrow"></span>
                    </li>
                </ul>
            </li>
            <li class="unfold">
            	<a href="#" class="first">服务管理(消费者)</a>
                <span class="arrow"></span>
                <ul class="sub">
				    <li>
                    	<a href="javascript:void(0);" onclick="showRightContent(this,'<?php echo ConfigManager::$baseurl; ?>/sc/addscpage');">添加新服务</a>
                		<span class="arrow"></span>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!--/sidebar-->
    <div id="id_server_content" class="main_content">        
        <img id="id_loading_spcontent" src="/img/anim_loading_75x75.gif" class="loading" style="display:none;"/> 
        <?php echo $content;?>      
    </div>
</div>
</div>

<script type="text/javascript">
var currenturl = "/sc/introduce";
function cleanLeftLiClass()
{
    $('#id_ul_list').children('li').each(function(){
       $(this).children('ul').children('li').each(function(){
            $(this).removeClass('cur');
       }); 
       
    });
}

function loadRightContent(url)
{
    $('#id_loading_spcontent').show();
    AjaxHtmlUrl(url,function(content){
        $('#id_server_content').html(content);
        $('#id_loading_spcontent').hide();        
    })
}
function showRightContent(ele,url)
{
    cleanLeftLiClass();
    $(ele).parent().addClass("cur");
    currenturl = url;
    loadRightContent(url);
}

function reloadRightContent()
{
    loadRightContent(currenturl);
}

loadRightContent(currenturl);
</script>

