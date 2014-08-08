<script type="text/javascript" src="<?php echo ConfigManager::$baseurl?>/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo ConfigManager::$baseurl?>/js/curve.js"></script>

<script>
$(document).ready(function(){
})
</script>

<div class="mod_iframe" style="padding: 20px 0 0px 40px;">
    <input type="hidden" id="id_current_projectid" value="<?php echo $projectinfo['_id'];?>"/>
    <h2 class="title" style="padding: 0 20px; margin-bottom: 10px;">
        <span id="id_current_project_name"><?php echo $projectinfo["name"];?> </span>
        <a href="javascript:void(0);" onclick="CurveManager.ChooseProject();" style="float: right;">choose</a>
    </h2>
</div>
<div style="position: relative;">
    <!--sidebar-->
     <div class="sidebar">
        <ul class="menu" id="id_ul_list">
            <?php foreach($curvelist as $curveid => $item){?>
            <li class="unfold">
            	<a href="javascript:void(0);" class="first"><?php echo $item["name"];?></a>
                <span class="arrow"></span>
                <ul class="sub">
    			    <li onclick="SelectThis(this);CurveManager.LoadCurve('<?php echo $projectinfo['_id'];?>','<?php echo $curveid;?>');">
                    	<a href="javascript:void(0);">动作统计</a>
                		<span class="arrow"></span>
                    </li>
                	<li>
                    	<a href="javascript:void(0);">日统计</a>
                		<span class="arrow"></span>
                    </li>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </div>
    <!--/sidebar-->
    <div id="id_curve_page"  class="mod_iframe" style="margin-left: 174px;">    
    </div>
</div>

<script>
$(document).ready(function(){
	$("li.fold").toggle(
	   function(){ $(this).parent().addClass("unfold");},
	   function(){ $(this).parent().removeClass("unfold");}	   
	   );
	$("li.unfold a.first").toggle(
	   function(){ $(this).parent().removeClass("unfold");$(this).parent().addClass("fold");},
	   function(){ $(this).parent().addClass("unfold");}
	   )
    var curveid = '<?php $ids = array_keys($curvelist); echo @$ids[0];?>';
    CurveManager.LoadCurve('<?php echo $projectinfo['_id'];?>', curveid);
    $("#id_ul_list li ul li :first").addClass("cur");
})

</script>