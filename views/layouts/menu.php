
<div class="nav">        	
    <a id="id_menu_site" href="Site/Index" ><span>首页</span></a>
    <a id="id_menu_account" href="Account/Index" ><span>Account管理</span></a>
    <a id="id_menu_project" href="Project/Index" ><span>Project</span></a>
    <a id="id_menu_curve" href="Curve/Index" ><span>曲线</span></a>
</div>

<script>
$("#id_menu_<?php echo strtolower($this->mainmenu);?>").addClass('cur');
</script>