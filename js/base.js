var HttpRequest = {
    basepath:""
};
function in_array(find,arr)
{
    for(var i=0; i<arr.length; ++i){
        if(arr[i]==find){return true;}
    }
    return false;
}

function Filter(filters)
{
    var filternames = [];
    if(typeof filters === "string"){
        filternames.push(filters);
    }else if(filters.constructor === Array){
        filternames = filters;
    }else{
        return;
    }
    
    var filtervalues = {};
    for(var i=0; i<filternames.length; ++i)
    {
        filtervalues[filternames[i]] = [];
        $(".cell_search input:checkbox[name='"+filternames[i]+"']:checked").each(function(){
            filtervalues[filternames[i]].push($(this).val()) ;
        });
    }
    
        
    $('.tableitem').each(function(){
        var itemele = this;
        
        var itemvalues = {};
        for(var i=0; i<filternames.length; ++i)
        {
            itemvalues[filternames[i]] = $(this).find(":input[name='"+filternames[i]+"']").val();;
        }
        //dump(itemvalues);
        var isShow = 1;
        for(var key in itemvalues){
            if(in_array(itemvalues[key],filtervalues[key])){
                isShow = isShow & 1; 
            }else{
                isShow = isShow & 0; 
            }
        }
        
        if(isShow)
        {
            $(itemele).show();
        }else{
            $(itemele).hide();
        }        
    });
}