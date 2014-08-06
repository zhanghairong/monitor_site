<?php
$addurl = '<?php echo ConfigManager::$baseurl;?>/'.ucfirst($shorttable)."/Add".ucfirst($shorttable);
?>

<div class="pop_container">
    <a href="javascript:close();" title="关闭" class="close"></a>
    <h2 class="title">添加<?php echo ucfirst($shorttable);?></h2>
    <div class="pop_content" style="width: 600px;">
        <div class="errortips">&nbsp;</div>
        <div class="cell_form">
            <form action="<?php echo $addurl;?>" method="GET" id="id_add_form">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <colgroup>
                        <col class="col_1" />
                        <col class="col_2" />
                        <col class="col_3" />
                    </colgroup>
<?php foreach($fields as $key=>$fieldname){
    if(in_array($fieldname,$skipfields)){continue;}
    $selectfieldnames = array_keys($selectfields);
?>
                    <tr>
                        <td><span class="tdtitle"><?php echo $fieldname;?></span></td>
<?php if(in_array($fieldname,$selectfieldnames)){?>
                        <?php echo $selectfields[$fieldname]["showlistcmd"];?>
                        
                        <td>
                            <select name="<?php echo $fieldname;?>">
                            <?php echo '<?php foreach($selectlist as $key => $value){?>'."\n";?>                            
                                <option value="<?php echo "<?php echo {$selectfields[$fieldname]["showkey"]};?>";?>" ><?php echo $selectfields[$fieldname]["showvalue"];?></option>
                            <?php echo "<?php }?>";?>                               
                            </select>
                        </td>
<?php }else{?>
                        <td><input name="<?php echo $fieldname;?>" type="text" class="text" value="" /></td>
<?php }?>
                        <td></td>
                    </tr>
<?php }?>
                </table>
                <div class="btn_box">
                    <a href="javascript:add();" class="btn" ><span>确 定</span></a>
                    <a href="javascript:close();" class="btn"><span>取 消</span></a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

function add()
{    
    var form = $("#id_add_form");
    
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
            $(".errortips").html("添加成功");
            unpophtml();
            window.location.reload();
        }else{
            $(".errortips").html("添加失败【"+ret['message']+"】，请重试");
        }
	});
}
function close()
{
    unpophtml();
}
</script>