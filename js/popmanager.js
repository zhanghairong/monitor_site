function getWindowScrollTop(win){
	var scrollTop=0;
	if(win.document.documentElement&&win.document.documentElement.scrollTop){
		scrollTop=win.document.documentElement.scrollTop;
	}else if(win.document.body){
		scrollTop=win.document.body.scrollTop;
	}
	return scrollTop;
}
function setWindowScrollTop(win, topHeight)
{
    if(win.document.documentElement)
    {
        win.document.documentElement.scrollTop = topHeight;
    }
    if(win.document.body){
        win.document.body.scrollTop = topHeight;
    }
}
function getWindowScrollLeft(win){
	var scrollLeft=0;
	if(win.document.documentElement&&win.document.documentElement.scrollLeft){
		scrollLeft=win.document.documentElement.scrollLeft;
	} else if(win.document.body){
		scrollLeft=win.document.body.scrollLeft;
	}
	return scrollLeft;
}
function getWindowHeight(win){
	var clientHeight=0;
	if(win.document.body.clientHeight&&win.document.documentElement.clientHeight){
		clientHeight = (win.document.body.clientHeight<win.document.documentElement.clientHeight)?
            win.document.body.clientHeight:win.document.documentElement.clientHeight;
	}else{
		clientHeight = (win.document.body.clientHeight>win.document.documentElement.clientHeight)?
            win.document.body.clientHeight:win.document.documentElement.clientHeight;
	}
	return clientHeight;
}
function getWindowWidth(win){
	var clientWidth=0;
	if(win.document.body.clientWidth&&win.document.documentElement.clientWidth){
		clientWidth = (win.document.body.clientWidth<win.document.documentElement.clientWidth)?
            win.document.body.clientWidth:win.document.documentElement.clientWidth;
	}else{
		clientWidth = (win.document.body.clientWidth>win.document.documentElement.clientWidth)?
            win.document.body.clientWidth:win.document.documentElement.clientWidth;
	}
	return clientWidth;
}

function unpop()
{
    try{
        var win = (top && top!=self)?top:window;
    }
    catch(e)
    {
        return ;
    }
    if(!win.document.getElementById('maskiframe'))
    {
        return;
    }
    win.document.getElementById('maskiframe').style.display = "none";
    win.document.getElementById("id_popdiviframe").style.display = "none";
    win.document.getElementById("id_iframe_pop").setAttribute('src', '');
    
}

function popup(url,width,height)
{
    try{
        var win = (top && top!=self)?top:self;
    }
    catch(e)
    {
        return ;
    }
    

    var topWindowHeight = getWindowHeight(win);
    var topWindowWidth = getWindowWidth(win);

    var lvTop=parseInt((topWindowHeight-height)/2)+parseInt(getWindowScrollTop(win));
    var lvLeft=parseInt((topWindowWidth-width)/2)+parseInt(getWindowScrollLeft(win));
    lvTop = lvTop<=0?1:lvTop;
    lvLeft = lvLeft<=0?1:lvLeft;
    

    win.document.getElementById("id_popdiviframe").style.top=lvTop+"px";
    win.document.getElementById("id_popdiviframe").style.left=lvLeft+"px";
    win.document.getElementById("id_popdiviframe").style.margin="0";
    
    //url = "http://www.baidu.com/";

    win.document.getElementById("id_iframe_pop").setAttribute('src', url);

            
    win.document.getElementById("id_iframe_pop").setAttribute('width', width);
    win.document.getElementById("id_iframe_pop").setAttribute('height', height);
    
    win.document.getElementById('maskiframe').style.display = "block";
    win.document.getElementById("id_popdiviframe").style.display = "block";
}

function setScrollTop(height)
{
    //alert(height);
    
    window.setTimeout(function(){
        setWindowScrollTop(top,height);
   	}, 20);
}


function unpophtml()
{
    try{
        var win = (top && top!=self)?top:window;
    }
    catch(e)
    {
        return ;
    }
    
    if(!win.document.getElementById("id_popdiv") || 
		!win.document.getElementById('mask'))
	{
		return;
	}
    
    win.$("#mask").hide();
    win.$("#id_popdiv").hide();        
    win.$("#id_popdiv").html('');
    
}


function popuphtml(url,width,height)
{
    try{
        var win = (top && top!=self)?top:window;
    }
    catch(e)
    {
        return ;
    }
    
    if(!win.document.getElementById("id_popdiv") || 
		!win.document.getElementById('mask'))
	{
		return;
	}
    
    
    var topWindowHeight = getWindowHeight(win);
    var topWindowWidth = getWindowWidth(win);

    var lvTop=parseInt((topWindowHeight-height)/2)+parseInt(getWindowScrollTop(win));
    var lvLeft=parseInt((topWindowWidth-width)/2)+parseInt(getWindowScrollLeft(win));
    lvTop = lvTop<=0?1:lvTop;
    lvLeft = lvLeft<=0?1:lvLeft;
    

    win.$("#id_popdiv").css('top',lvTop+"px");
    win.$("#id_popdiv").css('left',lvLeft+"px");
    win.$("#id_popdiv").css('margin',0);
    
    
    win.$("#id_popdiv").attr('width', width+"px");
    win.$("#id_popdiv").attr('height', height+"px");
        
    win.$("#mask").show();
    win.$("#id_popdiv").show();
    
    win.$("#id_pop_loading").show();
    win.$("#id_pop_content_html").hide();
    
    AjaxHtmlUrl(url,function(content){
        win.$("#id_popdiv").html(content);
        win.$("#id_pop_content_html").show();
        win.$("#id_pop_loading").hide();
    });
}

function showpopup(content)
{
    try{
        var win = (top && top!=self)?top:window;
    }
    catch(e)
    {
        return ;
    }
    
    if(!win.document.getElementById("id_popdiv") || 
		!win.document.getElementById('mask'))
	{
		return;
	}
    
    
    var topWindowHeight = getWindowHeight(win);
    var topWindowWidth = getWindowWidth(win);
    
    
    win.$("#id_popdiv").html(content);
    var width = win.$("#id_popdiv").width();
    var height = win.$("#id_popdiv").height();

    var lvTop=parseInt((topWindowHeight-height)/2)+parseInt(getWindowScrollTop(win));
    var lvLeft=parseInt((topWindowWidth-width)/2)+parseInt(getWindowScrollLeft(win));
    lvTop = lvTop<=0?1:lvTop;
    lvLeft = lvLeft<=0?1:lvLeft;
    

    win.$("#id_popdiv").css('top',lvTop+"px");
    win.$("#id_popdiv").css('left',lvLeft+"px");
    win.$("#id_popdiv").css('margin',0);
    
    
    win.$("#id_popdiv").attr('width', width+"px");
    win.$("#id_popdiv").attr('height', height+"px");
        
    win.$("#mask").show();
    win.$("#id_popdiv").show();
    
    win.$("#id_pop_content_html").hide();

    win.$("#id_pop_content_html").show();
}