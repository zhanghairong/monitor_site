
<div class="pop_container">
    <a href="javascript:close();" title="关闭" class="close"></a>
    <h2 class="title">修改曲线</h2>
    <div class="pop_content" style="width: 600px;">
        <div class="errortips">&nbsp;</div>
        <div class="cell_form">
            <form action="<?php echo ConfigManager::$baseurl;?>/Project/UpdateCurve" method="GET">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <colgroup>
                        <col class="col_1" />
                        <col class="col_2" />
                        <col class="col_3" />
                    </colgroup>
                    <tr>
                        <td><span class="tdtitle">_id</span></td>
                        <td><input name="_id" type="text" class="text notempty" value="<?php echo $info["_id"];?>" readonly="readonly" /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="tdtitle">projectid</span></td>
                        <td><input name="projectid" type="text" class="text notempty" value="<?php echo $info["projectid"];?>" readonly="readonly" /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="tdtitle">name</span></td>
                        <td><input name="name" type="text" class="text notempty" value="<?php echo $info["name"];?>" /></td>
                        <td></td>
                    </tr>
                </table>
                <div class="btn_box">
                    <a href="javascript:void(0);" onclick="ProjectManager.UpdateCurve(this,'<?php echo @$callback;?>');" class="btn" ><span>确 定</span></a>
                    <a href="javascript:close();" class="btn"><span>取 消</span></a>
                </div>
            </form>
        </div>
    </div>
</div>