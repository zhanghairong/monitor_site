
<div class="pop_container">
    <a href="javascript:close();" title="关闭" class="close"></a>
    <h2 class="title">编辑Account</h2>
    <div class="pop_content" style="width: 600px;">
        <div class="errortips">&nbsp;</div>
        <div class="cell_form">
            <form action="<?php echo ConfigManager::$baseurl;?>/Account/UpdateAccount" method="GET" id="id_update_form">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <colgroup>
                        <col class="col_1" />
                        <col class="col_2" />
                        <col class="col_3" />
                    </colgroup>
                    <tr>
                        <td><span class="tdtitle">_id</span></td>
                        <td><input name="_id" type="text" class="text" value="<?php echo @$info['_id'];?>" readonly="readonly" /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="tdtitle">account</span></td>
                        <td><input name="account" type="text" class="text" value="<?php echo @$info['account'];?>"  /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="tdtitle">passwd</span></td>
                        <td><input name="passwd" type="text" class="text" value="<?php echo @$info['passwd'];?>"  /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="tdtitle">privilege</span></td>
                        <td><input name="privilege" type="text" class="text" value="<?php echo @$info['privilege'];?>"  /></td>
                        <td></td>
                    </tr>
                </table>
                <div class="btn_box">
                    <a href="javascript:update();" class="btn" ><span>确 定</span></a>
                    <a href="javascript:close();" class="btn"><span>取 消</span></a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

function update()
{    
    var form = $("#id_update_form");
    
    $(form).find(":input").each(function(){
        $(this).val($(this).val().trim());
    });

    var hasemptyvalue = false;
    $(form).find(":input").each(function(){
        if($(this).val()==''){
            $(".errortips").html($(this).attr("name")+"不能为空");
            hasemptyvalue = true;
            return;
        } 
    });
    if(hasemptyvalue){return;}
    
    $(form).ajaxSubmit(function(ret){
        ret = eval("(" + ret + ")");
        if(ret.code==0){
            $(".errortips").html("修改成功");
            unpophtml();
            window.location.reload();
        }else{
            $(".errortips").html("修改失败【"+ret['message']+"】，请重试");
        }
	});
}
function close()
{
    unpophtml();
}
</script>