<script type="text/javascript">
$(document).ready(function(){    
    function ExectFileter()
    {
        Filter(['gender']);
    }
    
    $("input[type='checkbox'][name='gender']").change(function() {
        ExectFileter();
    });
});
</script>

<style>
.pages{text-align:center;margin:10px auto;color:#999;}
.pages a{margin:0px 1px;padding:2px 8px;border:1px solid #ccc;line-height:24px;font-size:13px;border-radius:3px;}
.pages a:hover{border: 1px solid #FF5500;color: #FF5500;}
.pageNO{width:28px;height:21px;margin:0px 3px;border:1px solid #aaa;font-size:13px;line-height:21px;text-align:center;border-radius:3px;}
</style>

<div class="mod_iframe" style="padding: 16px 0 0 4px;">
	<h2 class="title">Account列表</h2>
	<div class="mod_auto_contect">
        <div class="cell_search">
            <div class="list" id="id_search">
                <form action="<?php echo ConfigManager::$baseurl?>/Account/Index" >
                    <br/>
                    <span class="item3">
                        <label class="title">account ：</label>
                        <span class="option">
                            <input type="text" name="account" value="<?php echo @$searchvalues['account']?>"/>
                        </span>                  
                    </span>
                    <a href="javascript:void(0);" onclick="$(this).parents('form').submit();" class="btn"><span>查询</span></a>
                </form> 
            </div>
            
        </div>
        <p>&nbsp;</p>
		<div class="mod_tree cell_table">
            <div style="padding: 10px; text-align: right;">
                <a href="javascript:void(0);" onclick="Add();"><strong>添加新Userinfo</strong></a>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
                <colgroup>
                    <col style="width:15%;"/>
                    <col style="width:15%;"/>
                    <col style="width:15%;"/>
                    <col style="width:5%;"/>
                    <col style="width:15%;"/>
                    <col style="width:10%;"/>
                </colgroup>
                <tbody style="text-align:center;">
                    <tr class="title_1" style="background: #DDDDDD;">
                        <th>userid</th>
                        <th>account</th>
                        <th>passwd</th>
                        <th>privilege</th>
                        <th>createtime</th>
                        <th></th>
                    </tr>
                    <?php foreach($list as $key=> $info){?>
                    <tr class="tableitem">
                        <td><?php echo $info['_id'];?>&nbsp;</td>
                        <td><?php echo $info['account'];?>&nbsp;</td>
                        <td><?php echo @$info['passwd'];?>&nbsp;</td>
                        <td><?php echo @$info['privilege'];?>&nbsp;</td>
                        <td><?php echo date("Y-m-d H:i:s", $info['_id']->getTimestamp());?>&nbsp;</td>
                        <td>
                            <a href="javascript:void(0);" onclick="Edit('<?php echo $info["_id"];?>');" class="operate" title="edit">编辑</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="javascript:void(0);" onclick="Delete(this,'<?php echo $info["_id"];?>');" class="operate" title="delete">删除</a>
                        </td>	
                    </tr>			
                    <?php }?>
                </tbody>
            </table>
		</div>
	</div>
    
    <div class="pages"></div>
</div>

<script type="text/javascript">
new PageManager({pageSum:<?php echo $pagenum;?>});
    
function Add()
{
    var url = "<?php echo ConfigManager::$baseurl?>/Account/AddAccountPage";
    popuphtml(url,600,515);    
}
function Edit(id)
{
    var url = "<?php echo ConfigManager::$baseurl?>/Account/UpdateAccountPage?_id="+id
    popuphtml(url,600,515);
}
function Delete(ele,id)
{
    var url = "<?php echo ConfigManager::$baseurl?>/Account/DeleteAccount?_id="+id;
    AjaxJsonUrl(url,function(ret){
        if(ret['code']==0){
            $(ele).parents("tr").remove();
        }else{
            alert("删除失败，请稍后重试");
        }
    });    
}

</script>