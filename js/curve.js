
function close()
{
    unpophtml();
}

function SelectThis(ele){
    $("#id_ul_list").find("li").removeClass("cur");
    $(ele).addClass("cur");
}
var CurveManager = {
    ChooseProject:function(){
        var url = HttpRequest.basepath + "/Curve/ChooseProject?projectid="+$("#id_current_projectid").val();
        popuphtml(url,600,300);
    },
    ChooseProjectResult:function(ele) {
        var skip = "select_project_";
        var projectid = $(ele).attr("name").substr(skip.length);
        location.href = HttpRequest.basepath + "/Curve/Index?projectid=" + projectid
    },
    LoadActionCurve:function(projectid, curveid) {
        var url = HttpRequest.basepath + "/Curve/ActionCurvePage?projectid=" + projectid + "&curveid=" + curveid;
        LoadingManager.Show();
        AjaxHtmlUrl(url,function(ret){
            LoadingManager.Hide();
            $("#id_curve_page").html(ret);
        });
    },
    LoadDateCurve:function(projectid, curveid) {
        var url = HttpRequest.basepath + "/Curve/DateCurvePage?projectid=" + projectid + "&curveid=" + curveid;
        LoadingManager.Show();
        AjaxHtmlUrl(url,function(ret){
            LoadingManager.Hide();
            $("#id_curve_page").html(ret);
        });
    },
    LoadDailyReport:function(projectid, curveid) {
        var url = HttpRequest.basepath + "/Curve/DailyReport?projectid=" + projectid + "&curveid=" + curveid;
        LoadingManager.Show();
        AjaxHtmlUrl(url,function(ret){
            LoadingManager.Hide();
            $("#id_curve_page").html(ret);
        });
    }
};
var ChartLine = {
    highchart : null,
    
    LoadActionChart:function(name) {
        if ( ChartLine.highchart) {
            ChartLine.highchart = null;
        }
        ChartLine.highchart = ChartLine.NewHighChart(name);
        console.log(ChartLine.highchart);
        ChartLine.LoadActionLine();
    },
    LoadDateChart:function(name) {
        if ( ChartLine.highchart) {
            ChartLine.highchart = null;
        }
        ChartLine.highchart = ChartLine.NewHighChart(name);
        console.log(ChartLine.highchart);
        ChartLine.LoadDateLine();
    },
    LoadActionLine:function() {
        var form = $("#id_search");
        var projectid = $(form).find(":input[name='projectid']").val();
        var curveid = $(form).find(":input[name='curveid']").val();
        var day = $(form).find(":input[name='day']").val();
        var ips = [];
        $(form).find(":input[name='ip']:checked").each(function(){
            ips.push($(this).val())
        });
        groupbyip = $(form).find(":input[name='groupbyip']").is(":checked") ? 1 : 0;
        var url = HttpRequest.basepath + "/Curve/GetLineData?projectid=" + projectid + "&curveid=" + curveid + "&ips="+encodeURIComponent(ips.join(",")) +
                "&groupbyip="+groupbyip + "&day="+encodeURIComponent(day);
        console.log(url);        
        
        ChartLine.ClearLines();
        ChartLine.LoadData(url, "");
    },
    LoadDateLine:function() {
        var form = $("#id_search");
        var projectid = $(form).find(":input[name='projectid']").val();
        var curveid = $(form).find(":input[name='curveid']").val();
        var ips = [];
        $(form).find(":input[name='ip']:checked").each(function(){
            ips.push($(this).val())
        });
        var page = $(form).find(":input[name='page']").val();
        var url = HttpRequest.basepath + "/Curve/GetLineData?projectid=" + projectid + "&curveid=" + curveid + "&ips="+encodeURIComponent(ips.join(",")) +
                "&groupbyip=1&page="+encodeURIComponent(page);        
        
        ChartLine.ClearLines();
        $(form).find(":input[name='day']").each(function(){
            var day = $(this).val();
            var tmpurl = url + "&day="+encodeURIComponent(day);
            console.log(tmpurl);
            ChartLine.LoadData(tmpurl, "_" + day);
        });
        
    },
    LoadData:function(url, suffix) {
        //var url = HttpRequest.basepath + "/Curve/LoadLineTest?projectid=" + projectid + "&curveid=" + curveid;
        
        AjaxJsonUrl(url,function(ret){            
            if(ret.code != 0) {
                ErrorMessageManager.Show(ret.message, 3000);
                return;
            }
            for (var linename in ret.data.lines) {
                var line = {
                    name: linename + suffix,
                    pointStart: ChartLine.GetPointStart(), //(ret.data.begintime) * 1000,
                    pointInterval: ret.data.interval * 1000,
                    marker: { enabled: false },
                    data: []
                };
                
                var num = (24 * 3600 ) / ret.data.interval;
                for (var i = 1; i <= num; ++i) {
                    var timestamp = ret.data.begintime + ret.data.interval * i;
                    if (ret.data.lines[linename]["points"].hasOwnProperty(timestamp)) {
                        line.data.push(ret.data.lines[linename]["points"][timestamp.toString()]);
                    } else if(i < ret.data.lines[linename]["num"]){
                        line.data.push(0);
                    } else {
                        line.data.push(null);
                    }
                }
                console.log(line);
                ChartLine.highchart.addSeries(line);
            }
        });
    },
    GetPointStart:function() {
        var now = new Date();
        now.setHours(0);
        now.setMinutes(0);
        now.setSeconds(0);
        now.setMilliseconds(0);
        return now.getTime();
    },
    ClearLines:function(){
        while(ChartLine.highchart.series.length > 0) {
            ChartLine.highchart.series[0].remove();
        }
    },
    NewHighChart:function(title) {
        Highcharts.setOptions({global: { useUTC: false  }  }); 
        return new Highcharts.Chart({  
            chart: {
                renderTo: 'id_curve',
                type: 'line',
                zoomType : "xy"
            },        
            tooltip: {
                formatter: function() {
                    return "time: " + Highcharts.dateFormat('%H:%M', this.x) + "<br/>" +
                        this.series.name + ": " + this.y
                }  
            },
            title: {
                text: title
            },
            xAxis: {
                type:'datetime',
                labels: {
                    formatter: function() {
                        return  Highcharts.dateFormat('%H:%M', this.value);
                    }
                } 
            },
            yAxis: {
                title: {
                    text: ''
                },
                min : 0
            },
            legend: {
                align: 'right',
                verticalAlign: 'middle',
                layout: 'vertical',
            },
            series:[]
        });
    },
    
    LoadDailyData:function(){
        var form = $("#id_search");
        var projectid = $(form).find(":input[name='projectid']").val();
        var curveid = $(form).find(":input[name='curveid']").val();
        var day = $(form).find(":input[name='day']").val();
        var ips = [];
        $(form).find(":input[name='ip']:checked").each(function(){
            ips.push($(this).val())
        });
        groupbyip = $(form).find(":input[name='groupbyip']").is(":checked") ? 1 : 0;
        var url = HttpRequest.basepath + "/Curve/GetDailyReportData?projectid=" + projectid + "&curveid=" + curveid + "&ips="+encodeURIComponent(ips.join(",")) +
                "&groupbyip="+groupbyip + "&day="+encodeURIComponent(day);
        console.log(url);        
        AjaxHtmlUrl(url,function(ret){            
            $("#id_reportdata").html(ret);
        });
    }
};
