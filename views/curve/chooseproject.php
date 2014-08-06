<div class="pop_container" style="width: 700px;">
    <a href="javascript:close();" title="关闭" class="close"></a>
    <h2 class="title">选择Project</h2>
    <div class="pop_content mod_auto_contect">
        <div class="cell_search2 select_projectlist">
            <div class="list">
            <?php foreach($projectlist as $projectid => &$projectitem){?>
                <span class="item4 ">
                    <button onclick="CurveManager.ChooseProjectResult(this);" class="button" name="select_project_<?php echo $projectid;?>">
                        <?php echo $projectitem["name"];?> 
                    </button>        
                </span>  
            <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    var projectid = "<?php echo $currentprojectid;?>";
    $(".select_projectlist").find("button").removeClass("current");
    var filter = "button[name='select_project_"+projectid+"']";    
    $(".select_projectlist").find(filter).addClass("current");
})

</script>