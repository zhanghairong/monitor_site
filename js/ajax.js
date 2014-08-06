var AjaxUrl = AjaxJsonUrl;
function AjaxJsonUrl(url,callBack,timeoutseconds)
{
    $.ajax({
    	url:url+(url.indexOf("?")>0?"&":"?")+"_t="+(new Date).getTime(),
    	type : 'get',
    	dataType : 'json',
    	//cache : false,
    	success : function(data) {
    		if(data){
    			try {
    				callBack && callBack(data);
    			} catch(err) {}
    		} else {
    			var ret = {"status":false,"code":-2, "message":"invalid json response packet"};
    			try {
    				callBack && callBack(ret);
    			} catch(err) {}
    		}
    	},
    	error : function(url) {
    		var ret = {"status":false, "code":-3, "message":"ajax call failed"};
    		try {
    			callBack && callBack(ret);
    		} catch(err) {}
    	},
    	timeout:timeoutseconds!=undefined?timeoutseconds:10000
    });		
}
function SyncJsonUrl(url,callBack)
{
    $.ajax({
    	url:url+(url.indexOf("?")>0?"&":"?")+"_t="+(new Date).getTime(),
    	type : 'get',
    	dataType : 'json',
    	async : false,
    	success : function(data) {
    		if(data){
    			try {
    				callBack && callBack(data);
    			} catch(err) {}
    		} else {
    			var ret = {"status":false,"code":-2, "message":"invalid json response packet"};
    			try {
    				callBack && callBack(ret);
    			} catch(err) {}
    		}
    	},
    	error : function(url) {
    		var ret = {"status":false, "code":-3, "message":"ajax call failed"};
    		try {
    			callBack && callBack(ret);
    		} catch(err) {}
    	},
    	timeout:10000
    });		
}
function AjaxHtmlUrl(url,callBack,timeoutseconds)
{
    $.ajax({
    	url:url+(url.indexOf("?")>0?"&":"?")+"_t="+(new Date).getTime(),
    	type : 'get',
    	dataType : 'html',
    	cache : true,
    	success : function(data) {
    		if(data){
    			try {
    				callBack && callBack(data);
    			} catch(err) {}
    		} else {
    			var ret = 'invalid html response packet';
    			try {
    				callBack && callBack(ret);
    			} catch(err) {}
    		}
    	},
    	error : function(url) {
    		var ret = "ajax call failed";
    		try {
    			callBack && callBack(ret);
    		} catch(err) {}
    	},
    	timeout:timeoutseconds!=undefined?timeoutseconds:10000
    });		
}

function AjaxUploadFile(formid,callback)
{
    var funname = 'callback'+(new Date).getTime();
    this[funname] = callback;
    var tempurl = document.getElementById(formid).action;
    document.getElementById(formid).action = tempurl + (0<tempurl.search(/\?/)?'&':'?') + 'callback='+funname;
    
    $iframeid = 'iframe'+(new Date).getTime();
    var iframehtml = "<iframe name='"+$iframeid+"' id='"+$iframeid+"' style='display: none;'></iframe> ";
    $(document.body).append(iframehtml);
    $('#'+formid).attr("target", $iframeid);
    
    document.getElementById(formid).submit();
    
    document.getElementById(formid).action = tempurl
}