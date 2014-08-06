<?php
$updateurl = '<?php echo ConfigManager::$baseurl;?>/'.ucfirst($shorttable)."/Update".ucfirst($shorttable);
?>

<div class="pop_container">
    <a href="javascript:close();" title="关闭" class="close"></a>
    <h2 class="title">编辑<?php echo ucfirst($shorttable);?></h2>
    <div class="pop_content" style="width: 600px;">
        <div class="errortips">&nbsp;</div>
        <div class="cell_form">
            <form action="<?php echo $updateurl;?>" method="GET" id="id_update_form">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <colgroup>
                        <col class="col_1" />
                        <col class="col_2" />
                        <col class="col_3" />
                    </colgroup>
<?php foreach($fields as $key=>$fieldname){
    if(in_array($fieldname,$skipfields)){continue;}
    $readonly = false;
    if(in_array($fieldname,$readonlyfields)){$readonly = true;}
    $selectfieldnames = array_keys($selectfields);
?>
                    <tr>
                        <td><span class="tdtitle"><?php echo $fieldname;?></span></td>
<?php if(in_array($fieldname,$selectfieldnames)){?>
                        <?php echo $selectfields[$fieldname]["showlistcmd"]."\n";?>
                        <td>
                            <select name="<?php echo $fieldname;?>" <?php if($readonly){echo 'readonly="readonly"';}?> >
                            <?php echo '<?php foreach($selectlist as $key => $value){?>'."\n";?>
                                <option value="<?php echo "<?php echo {$selectfields[$fieldname]["showkey"]};?>";?>" <?php echo "<?php echo {$selectfields[$fieldname]['showkey']}==\$info['{$fieldname}']?'selected=\"selected\"':'';?>";?> ><?php echo $selectfields[$fieldname]["showvalue"];?></option>
                            <?php echo "<?php }?>";?>   
                            </select>
                        </td>
<?php }else{?>
                        <td><input name="<?php echo $fieldname;?>" type="text" class="text" value="<?php echo "<?php echo @\$info['{$fieldname}'];?>";?>" <?php if($readonly){echo 'readonly="readonly"';}?> /></td>
<?php }?>
                        <td></td>
                    </tr>
<?php }?>
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