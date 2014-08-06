
<div class="pop_container">
    <h2 class="title">登录</h2>
    <div class="pop_content" style="width: 600px;">
        <div class="errortips">&nbsp;</div>
        <div class="cell_form">
            <form action="<?php echo ConfigManager::$baseurl;?>/Login/LoginByAccount" method="GET" id="id_form">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <colgroup>
                        <col class="col_1" />
                        <col class="col_2" />
                        <col class="col_3" />
                    </colgroup>
                    <tr>
                        <td><span class="tdtitle">account</span></td>
                        <td><input name="account" type="text" class="text" value="" /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span class="tdtitle">password</span></td>
                        <td><input name="password" type="text" class="text" value="" /></td>
                        <td></td>
                    </tr>
                </table>
                <div class="btn_box">
                    <a href="javascript:void(0);" class="btn" onclick="DoLogin();"><span>登  录</span></a>
                </div>
            </form>
                
            <input type="hidden" value="<?php echo $backurl;?>" id="id_backurl"/>
        </div>
    </div>
</div>

<script>
function DoLogin()
{
    var form = $("#id_form");
    hasemptyvalue = false;
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
            location.href = $("#id_backurl").val();
        }else{
            $(".errortips").html("登录失败【"+ret['message']+"】，请重试");
        }
	});
}


</script>
