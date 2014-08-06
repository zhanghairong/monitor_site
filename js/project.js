
function close()
{
    unpophtml();
}
var ProjectManager = {
    ShowAddProject:function()
    {
        var url = HttpRequest.basepath + "/Project/AddProjectPage";
        popuphtml(url,600,515);
    },
    ShowEditProject:function(ele)
    {
        var projectid = $(ele).parents("p").find(".projectid").html();
        var url = HttpRequest.basepath + "/Project/UpdateProjectPage?projectid="+projectid
        popuphtml(url,600,515);
    },
    AddProject:function()
    {    
        var form = $("#id_add_form");
        
        $(form).find(":input").each(function(){
            $(this).val($(this).val().trim());
        });
    
        var hasemptyvalue = false;
        $(form).find(":input").each(function(){
            if($(this).hasClass("notempty") && $(this).val()==''){
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
    },
    UpdateProject:function()
    {    
        var form = $("#id_update_form");
        
        $(form).find(":input").each(function(){
            $(this).val($(this).val().trim());
        });
    
        var hasemptyvalue = false;
        $(form).find(":input").each(function(){
            if($(this).hasClass("notempty") && $(this).val()==''){
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
    },
    
    
    LoadCurveList:function(projectid) {
        url = HttpRequest.basepath + "/Project/CurveList?projectid="+projectid;
        AjaxHtmlUrl(url,function(ret){
            $("." + projectid).find(".curvegroup").html(ret);
        });
    },
    
    fold:function(ele) {
        var liele = $(ele).parents("li");
        $(liele).toggleClass("unfold");
        if($(liele).hasClass("unfold") && $.trim($(liele).find(".curvegroup").html()) == ""){
            var projectid = $(liele).find(".projectid").html();
            ProjectManager.LoadCurveList(projectid);
        }
    },
    
    ShowAddCurve:function(ele) {
        var projectid = $(ele).parents("ul .sub").find(":input[name='projectid']").val();
        var funcname = "AddCurveResult"+(new Date).getTime();
        window[funcname] = function(){
             ProjectManager.LoadCurveList(projectid);
        }
        var url = HttpRequest.basepath + "/Project/AddCurvePage?projectid="+projectid+"&callback="+funcname;
        popuphtml(url,600,515);
    },
    
    AddCurve:function(ele, callback) {
        var form = $(ele).parents("form");
        
        $(form).find(":input").each(function(){
            $(this).val($(this).val().trim());
        });
    
        var hasemptyvalue = false;
        $(form).find(":input").each(function(){
            if($(this).hasClass("notempty") && $(this).val()==''){
                $(".errortips").html($(this).attr("name")+"不能为空");
                hasemptyvalue = true;
                return;
            } 
        });
        if(hasemptyvalue){return;}
        
        $(form).ajaxSubmit(function(ret){
            ret = eval("(" + ret + ")");
            if(ret.code==0){
                unpophtml();
                eval("(" + callback + "())");
            }else{
                $(".errortips").html("添加失败【"+ret['message']+"】，请重试");
            }
    	});
    },
    
    ShowEditCurve:function(ele) {
        var projectid = $(ele).parents("ul .sub").find(":input[name='projectid']").val();
        var curveid = $(ele).parents("li").find(":input[name='curveid']").val();
    
        var funcname = "UpdateCurveResult"+(new Date).getTime();
        window[funcname] = function(){
            ProjectManager.LoadCurveList(projectid);
        }
        var url = HttpRequest.basepath + "/Project/UpdateCurvePage?curveid="+curveid+"&callback="+funcname;
        popuphtml(url,600,515);
    },
    
    UpdateCurve:function(ele, callback) {
        var form = $(ele).parents("form");
        
        $(form).find(":input").each(function(){
            $(this).val($(this).val().trim());
        });
    
        var hasemptyvalue = false;
        $(form).find(":input").each(function(){
            if($(this).hasClass("notempty") && $(this).val()==''){
                $(".errortips").html($(this).attr("name")+"不能为空");
                hasemptyvalue = true;
                return;
            } 
        });
        if(hasemptyvalue){return;}
        
        $(form).ajaxSubmit(function(ret){
            ret = eval("(" + ret + ")");
            if(ret.code==0){
                unpophtml();
                eval("(" + callback + "())");
            }else{
                $(".errortips").html("更新失败【"+ret['message']+"】，请重试");
            }
    	});
    },
    DeleteCurve:function(ele) {
        var projectid = $(ele).parents("ul .sub").find(":input[name='projectid']").val();
        var curveid = $(ele).parents("li").find(":input[name='curveid']").val();
    
        var url = HttpRequest.basepath + "/Project/DeleteCurve?curveid="+curveid;
        LoadingManager.Show();
        AjaxJsonUrl(url,function(ret){
            LoadingManager.Hide();
            if(ret['code'] != 0){
                ErrorMessageManager.Show(ret["message"]);
            } else {
                ProjectManager.LoadCurveList(projectid);
            }
        })
    }
}