
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
    LoadCurve:function(projectid, curveid) {
        var url = HttpRequest.basepath + "/Curve/LoadCurvePage?projectid=" + projectid + "&curveid=" + curveid;
        LoadingManager.Show();
        AjaxHtmlUrl(url,function(ret){
            LoadingManager.Hide();
            $("#id_curve_page").html(ret);
        });
    }
};
var ChartLine = {
    highchart : null,
    
    LoadCurveData:function(projectid,curveid,name) {
        if ( ChartLine.highchart) {
            ChartLine.highchart = null;
        }
        ChartLine.highchart = ChartLine.NewHighChart(name);
        console.log(ChartLine.highchart);
        ChartLine.LoadData(projectid, curveid);
    },
    ReloadData:function(ele) {
        var form = $(ele).parents("form");
        var projectid = $(form).find(":input[name='projectid']").val();
        var curveid = $(form).find(":input[name='curveid']").val();
        var day = $(form).find(":input[name='day']").val();
        ChartLine.LoadData(projectid, curveid, day);
    },
    LoadData:function(projectid,curveid, day) {
        var url = HttpRequest.basepath + "/Curve/GetLineData?projectid=" + projectid + "&curveid=" + curveid;
        //var url = HttpRequest.basepath + "/Curve/LoadLineTest?projectid=" + projectid + "&curveid=" + curveid;
        if (typeof day != "undefined"){
            url += "&day="+encodeURIComponent(day);
        }
        ChartLine.ClearLines();
        console.log(url);
        AjaxJsonUrl(url,function(ret){            
            if(ret.code != 0) {
                return;
            }
            //console.log(ret.data);
            for (var linename in ret.data.lines) {
                var line = {
                    name: linename,
                    pointStart: (ret.data.begintime + (8 * 3600) + ret.data.interval) * 1000,
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
    ClearLines:function(){
        while(ChartLine.highchart.series.length > 0) {
            ChartLine.highchart.series[0].remove();
        }
    },
    NewHighChart:function(title) {
        return new Highcharts.Chart({  
            chart: {
                renderTo: 'id_curve',
                type: 'line',
                zoomType : "xy"
            },  
            title: {
                text: title
            },
            xAxis: {
                type:'datetime',
                labels: {
                    formatter: function() {
                        return Highcharts.dateFormat('%H:%M', this.value);
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
    }
};
