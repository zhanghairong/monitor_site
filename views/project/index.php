<script type="text/javascript" src="<?php echo ConfigManager::$baseurl?>/js/project.js"></script>
<div class="mod_iframe">
	<h2 class="title">Project列表</h2>
	<div class="mod_auto_contect">
		<div class="mod_tree">
			<ul class="menu"  id="tree_menu">
                <?php foreach($list as $projectid => $item){?>
				<li class="<?php echo $projectid;?>">
					<p class="title_1">
						<a href="javascript:void(0);" class="icon_fold" onclick="ProjectManager.fold(this);"></a>						
                        <span class="domaininfo">
                            <span><b><?php echo $item["name"];?></b>(<span class="projectid"><?php echo $projectid;?></span>)</span>
                            <!--span style="margin-left: 20px;max-width: 50%;" title="<?php echo @$item["ip"];?>">
                            <?php echo @$item["ip"];?>
                            </span-->
                        </span>                      
						<a href="javascript:void(0);" class="link" onclick="ProjectManager.ShowEditProject(this);" title="edit" style="float: right;">编辑</a>                        
					</p>
					<span class="curvegroup"> </span>
				</li>
                <?php }?>
				<li class="fold">
					<p class="title_1">
					  <a href="javascript:void(0);" onclick="ProjectManager.ShowAddProject(this);"><strong>添加新项目</strong></a>
					</p>
				</li>
			</ul>
		</div>
	</div>
</div>

