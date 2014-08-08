<div>
    <div class="cell_search" style="display: inline-block;position: relative;width: 100%;margin-top: -11px;">
        <div class="">
            <form>
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
                        <input type="text" name="ip" value=""/>
                    </span>
                </span>
                <a href="javascript:void(0);" onclick="ChartLine.ReloadData(this);" class="btn"><span>查询</span></a>
            </form>
        </div>            
    </div>
    <div id="id_curve" style="height:600px;"> </div>
</div>



<script>
$(document).ready(function(){
    $("#id_day").date_input();
    document.getElementById("id_day").value = "<?php echo $date;?>";//(new Date).format("YYYY-MM-DD");
    ChartLine.LoadCurveData("<?php echo $projectid;?>", "<?php echo $curveid;?>", "<?php echo $curvename;?>");
});
</script>