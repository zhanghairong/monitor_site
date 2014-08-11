<div>
    <div class="cell_search" style="display: inline-block;position: relative;width: 100%;margin-top: -11px;">
        <div class="">
            <form id="id_search">
                <input type="hidden" name="projectid" value="<?php echo $projectid;?>"/>
                <input type="hidden" name="curveid" value="<?php echo $curveid;?>"/>
                <span class="item5">
                    <label class="title">date：</label>
                    <span class="option">
                        <input id="id_day" type="text" name="day" class="select_box"/>
                    </span>                  
                </span>
                <br />
                <span class="item5">
                    <label class="title">ip:</label>
                    <span class="option">
                    <?php foreach($ips as $key => $ip){ ?>
                        <span class="ip_segment">
                            <input type="checkbox" name="ip" value="<?php echo $ip; ?>" checked="checked"/>
                            <label><?php echo $ip; ?></label>
                        </span>
                    <?php } ?>
                    </span>
                </span>
                <span class="item5" style="float: right;margin-right: 30px;margin-top: -35px;">
                    <span class="option">
                        <input type="checkbox" name="groupbyip" value="1" checked="checked"/>
                        <label>groupbyip</label>
                    </span>
                </span>
                <a href="javascript:void(0);" onclick="ChartLine.LoadActionLine();" class="btn"><span>查询</span></a>
            </form>
        </div>            
    </div>
    <div id="id_curve" style="height:600px;"> </div>
</div>



<script>
$(document).ready(function(){
    $("#id_day").date_input();
    document.getElementById("id_day").value = "<?php echo $day;?>";//(new Date).format("YYYY-MM-DD");
    ChartLine.LoadActionChart("<?php echo $curvename;?>" + "(动作对比)");
});
console.log((new Date).getTimezoneOffset());
</script>