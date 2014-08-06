<ul class="sub">
    <input type="hidden" name="projectid" value="<?php echo $projectid;?>"/>
    <?php foreach($list as $curveid => $item){  ?>
	<li class="unfold">
		<p class="title_2">	
            <input type="hidden" name="curveid" value="<?php echo $curveid;?>"/>
            <span class="code"><?php echo $item["name"];?>(<?php echo $curveid;?>)</span>
			<a href="javascript:void(0);" onclick="ProjectManager.ShowEditCurve(this);" class="link">编辑</a>  
			<a href="javascript:void(0);" onclick="ProjectManager.DeleteCurve(this);" class="link">删除</a>                               
	  </p>
	</li>
    <?php }?>
	<li class="fold">
        <p class="title_2">
			<a href="javascript:void(0);" onclick="ProjectManager.ShowAddCurve(this);">添加曲线</a>
	    </p>							
	</li>
</ul>

