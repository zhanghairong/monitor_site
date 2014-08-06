<script type="text/javascript">
$(document).ready(function(){    
<?php $selectedkeys = array_keys($selectfields);?>
    function ExectFileter()
    {
        Filter(['<?php echo implode("','",$selectedkeys);?>']);
    }
    
<?php foreach($selectedkeys as $key => $selectfieldname){?>
    $("input[type='checkbox'][name='<?php echo $selectfieldname;?>']").change(function() {
        ExectFileter();
    });
<?php }?>
});
</script>

<div class="mod_iframe" style="padding: 16px 0 0 4px;">
	<h2 class="title"><?php echo ucfirst($shorttable);?>列表</h2>
	<div class="mod_auto_contect">
        <div class="cell_search">
            <div class="list" id="id_search"> 
                <br/>
<?php foreach($selectfields as $filterkey => $selectinfo){?>
                <?php echo $selectinfo["showlistcmd"]."\n";?>
                <span class="item">
                    <label class="title"><?php echo $filterkey;?> ：</label>
                    <?php echo '<?php foreach($selectlist as $key => $value){?>'."\n";?>       
                    <span class="option">
                        <input type="checkbox" name="<?php echo $filterkey;?>" value="<?php echo "<?php echo {$selectinfo['showkey']};?>";?>" checked="checked"/>
                        <label><?php echo $selectinfo["showvalue"];?></label>
                    </span>
                    <?php echo "<?php }?>\n"; ?>                    
                </span> 
                <br/>
<?php }?>
            </div>
<?php if(!empty($searchfields)){?>
            <div class="list" id="id_search">
                <form action="<?php echo "<?php echo ConfigManager::\$baseurl?>/{$shorttable}/Index"; ?>" >
                    <br/>
<?php foreach($searchfields as $key => $searchfieldname){?>
                    <span class="item3">
                        <label class="title"><?php echo $searchfieldname;?> ：</label>
                        <span class="option">
                            <input type="text" name="<?php echo $searchfieldname;?>" value="<?php echo "<?php echo @\$searchvalues['{$searchfieldname}']?>";?>"/>
                        </span>                  
                    </span> 
<?php }?>
                    <a href="javascript:void(0);" onclick="$(this).parents('form').submit();" class="btn"><span>查询</span></a>
                </form> 
            </div>
<?php }?>            
        </div>
        <p>&nbsp;</p>
		<div class="mod_tree cell_table">
            <div style="padding: 10px; text-align: right;">
                <a href="javascript:void(0);" onclick="Add();"><strong>添加新<?php echo ucfirst($shorttable);?></strong></a>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
                <colgroup>
<?php if(isset($colgroup)){
    foreach($colgroup as $key=>$width){
?>
                    <col style="width:<?php echo $width;?>;"/>
<?php }}else {
    foreach($fields as $key=>$fieldname){
?>
                    <col style="width:10%;"/>
<?php }?>
                    <col style="width:10%;"/>
<?php }?>
                </colgroup>
                <tbody style="text-align:center;">
                    <tr class="title_1" style="background: #DDDDDD;">
<?php foreach($fields as $key=>$fieldname){?>
                        <th><?php echo $fieldname;?></th>
<?php }?>
                        <th></th>
                    </tr>
                    <?php echo '<?php foreach($list as $key=> $info){?>'."\n"; ?>
                    <tr class="tableitem">
<?php foreach($fields as $key=>$fieldname){
    if(isset(ConfigManager::$config['params']["tableconfig"][$table][$fieldname])){?>
                        <td>
                            <span class="shortitem">
                            <input type="hidden" name="<?php echo $fieldname;?>" value="<?php echo "<?php echo \$info['{$fieldname}'];?>"; ?>"/>
                            <?php echo "<?php echo ConfigManager::\$config['params']['tableconfig']['{$table}']['{$fieldname}'][\$info['{$fieldname}']];?>&nbsp;\n";?>
                            </span> 
                        </td>
<?php }else if(in_array($fieldname,$selectedkeys)){?>
                        <td><input type="text" name="<?php echo $fieldname;?>" value="<?php echo "<?php echo \$info['{$fieldname}'];?>"; ?>" readonly="readonly" class="readonly"/></td>
<?php }else{?>
                        <td><?php echo "<?php echo \$info['{$fieldname}'];?>"; ?>&nbsp;</td>
<?php }}?>

                        <td>
                            <a href="javascript:void(0);" onclick="Edit(<?php echo '<?php echo $info["idx"];?>';?>);" class="operate" title="edit">编辑</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="javascript:void(0);" onclick="Delete(this,<?php echo '<?php echo $info["idx"];?>';?>);" class="operate" title="delete">删除</a>
                        </td>	
                    </tr>			
                    <?php echo '<?php }?>'."\n"; ?>
                </tbody>
            </table>
		</div>
	</div>
</div>

<?php
$addurl = '<?php echo ConfigManager::$baseurl?>/'.ucfirst($shorttable)."/Add".ucfirst($shorttable)."Page";
$editurl = '<?php echo ConfigManager::$baseurl?>/'.ucfirst($shorttable)."/Update".ucfirst($shorttable)."Page";
$deleteurl = '<?php echo ConfigManager::$baseurl?>/'.ucfirst($shorttable)."/Delete".ucfirst($shorttable);
?>
<script type="text/javascript">
function Add()
{
    var url = "<?php echo $addurl;?>";
    popuphtml(url,600,515);    
}
function Edit(id)
{
    var url = "<?php echo $editurl;?>?idx="+id
    popuphtml(url,600,515);
}
function Delete(ele,id)
{
    var url = "<?php echo $deleteurl;?>?idx="+id;
    AjaxJsonUrl(url,function(ret){
        if(ret['code']==0){
            $(ele).parents("tr").remove();
        }else{
            alert("删除失败，请稍后重试");
        }
    });    
}

</script>