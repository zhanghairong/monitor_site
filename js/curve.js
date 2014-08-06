
function close()
{
    unpophtml();
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
        if ( ChartLine.highchart === null) {
            ChartLine.highchart = ChartLine.NewHighChart(name);
        }
        console.log(ChartLine.highchart);
        ChartLine.LoadData(projectid, curveid);
    },
    LoadData:function(projectid,curveid) {
        var url = HttpRequest.basepath + "/Curve/LoadLineTest?projectid=" + projectid + "&curveid=" + curveid;
        AjaxJsonUrl(url,function(ret){            
            if(ret.code != 0) {
                return;
            }
            //console.log(ret.data);
            for (var linename in ret.data.lines) {
                var line = {
                    name: linename,
                    pointStart: (ret.data.begintime + (8 * 3600)) * 1000,
                    pointInterval: ret.data.interval * 1000,
                    data: []
                };
                
                var num = (24 * 3600 ) / ret.data.interval;
                for (var i = 0; i < num; ++i) {
                    var timestamp = ret.data.begintime + ret.data.interval * i;
                    if (ret.data.lines[linename].hasOwnProperty(timestamp)) {
                        line.data.push(ret.data.lines[linename][timestamp]);
                    } else{
                        line.data.push(null);
                    }
                }
                //console.log(line);
                ChartLine.highchart.addSeries(line);
            }
        });
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
            }
        });
    }
};
