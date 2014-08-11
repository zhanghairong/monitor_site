<div>
    <div class="cell_search" style="display: inline-block;position: relative;width: 100%;margin-top: -11px;">
        <div class="">
            <form id="id_search">
                <input type="hidden" name="projectid" value="<?php echo $projectid;?>"/>
                <input type="hidden" name="curveid" value="<?php echo $curveid;?>"/>
                <span class="item5">
                    <label class="title">date1：</label>
                    <span class="option value">
                        <input id="id_day1" type="text" name="day" class="select_box"/>
                    </span>
                </span>
                <span class="item5">
                    <label class="title">date2：</label>
                    <span class="option value">
                        <input id="id_day2" type="text" name="day" class="select_box"/>
                    </span>                  
                </span>
                <span class="item5">
                    <label class="title">pages：</label>
                    <span class="option value">
                        <select name="page">
                            <?php foreach($pages as $key => $page){ ?>
                            <option value="<?php echo $page;?>"><?php echo $page;?></option>
                            <?php } ?>
                        </select>
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
                <a href="javascript:void(0);" onclick="ChartLine.LoadDateLine();" class="btn"><span>查询</span></a>
            </form>
        </div>            
    </div>
    <div id="id_curve" style="height:600px;"> </div>
</div>



<script>
$(document).ready(function(){
    $("#id_day1").date_input();
    $("#id_day2").date_input();
    document.getElementById("id_day1").value = "<?php echo $day1;?>";//(new Date).format("YYYY-MM-DD");
    document.getElementById("id_day2").value = "<?php echo $day2;?>";
    ChartLine.LoadDateChart("<?php echo $curvename;?>" + "(多日期对比)");
});
</script>