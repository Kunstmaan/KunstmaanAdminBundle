   $(document).ready(function() {
        $(window).resize(function (){
            initChart();
        });
    });

    // load dashboard data
    var url = "analytics/getDailyOverview";
    var chartData = [];
    var chartLabels = [];
    var dailyOverview = [];

    $.get(url, function(data) {
        dailyOverview = data.dailyOverview;
        var data = data.dailyOverview.slice(0,7);
        setChartData(data, true);
    });

    // sets the chart data
    function setChartData(data) {
        chartData = [];
        chartLabels = [];

        var increment = Math.ceil(data.length / 26);
        for (var i = 0; i < data.length; i+=increment) {
                chartData.push(parseInt(data[i].visits));
                chartLabels.push(data[i].timestamp);
        }

        initChart();
    }

    // sets chart width and height
    function resizeChart() {
        var chartWidth = $('#js-dashboard-chart').parent().width();
        var chartHeight = $('#js-dashboard-chart').height();
        $('#js-dashboard-chart').attr('width', chartWidth );
        $('#js-dashboard-chart').attr('height', chartHeight );
    }

    // inits the chart
    initChart = function() {

        var barChartData = {
            labels : chartLabels,
            datasets : [
                {
                    fillColor : "rgba(41, 151, 206, 0.3)",
                    strokeColor : "rgb(41, 151, 206)",
                    pointColor : "rgb(41, 151, 206)",
                    pointStrokeColor : "#fff",
                    data : chartData,
                    scaleShowLabels : true
                }
            ]
        };

        resizeChart();

        // only use animation in browsers who are not IE8
        var myLine = new Chart(document.getElementById("js-dashboard-chart").getContext("2d")).Line(barChartData, {animation:false});
    };

    function setChart(data) {
        setChartData(data.overview.chartData);
    }
