var LoadingManager = 
{
	toastele:null,
    SetToastrOptions:function(){
        toastr.options = {
    		"closeButton": false,
    		"debug": false,
    		"positionClass": "toast-center",
            containerId: "toast-loading-container",
    		"onclick": null,
    		"showDuration": "1000",
    		"hideDuration": "1000",
    		"timeOut": "0",
    		"extendedTimeOut": "0",
    		"showEasing": "swing",
    		"hideEasing": "linear",
    		"showMethod": "show",
    		"hideMethod": "hide",
			"tapToDismiss": false
    	};
    },
    
    Show:function(){
        LoadingManager.Hide();
		LoadingManager.SetToastrOptions();
        LoadingManager.toastele = toastr.loading("<div class='loading spin'></div>", "");
    },
    
    Hide:function(){
		if(LoadingManager.toastele){
			toastr.clear(LoadingManager.toastele);
			LoadingManager.toastele = null;
		}
    }
};

var ErrorMessageManager = 
{
	toastele:null,
    SetToastrOptions:function(timeout){
        toastr.options = {
    		"closeButton": false,
    		"debug": true,
    		"positionClass": "toast-center",
            containerId: "toast-container",
    		"onclick": null,
    		"showDuration": "1000",
    		"hideDuration": "1000",
    		"timeOut": typeof timeout!="undefined"?timeout:"5000",
    		"extendedTimeOut": typeof timeout!="undefined"?timeout:"5000",
    		"showEasing": "swing",
    		"hideEasing": "linear",
    		"showMethod": "show",
    		"hideMethod": "hide",
			"tapToDismiss": false
    	};
    },
    
    Show:function(message,timeout){
		ErrorMessageManager.Hide();
		ErrorMessageManager.SetToastrOptions(timeout);
        ErrorMessageManager.toastele = toastr.error(message,"");
    },
    
    Hide:function(){
		if(ErrorMessageManager.toastele){
			toastr.clear(ErrorMessageManager.toastele);
			ErrorMessageManager.toastele = null;
		}
    }
};