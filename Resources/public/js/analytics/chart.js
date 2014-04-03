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
    function setChartData(data, showLabels) {
        chartData = [];
        chartLabels = [];

        if (data != null) {
            for (var i = 0; i < data.length; i++) {
                chartData.push(parseInt(data[i].data));
                if (showLabels == true || i % 5 == 0) {
                    chartLabels.push(data[i].key);
                } else {
                    chartLabels.push("");
                }
            }
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
        // in IE8 the animation is too slow and jerky
        if (!$('html').hasClass('ie8')) {
            var myLine = new Chart(document.getElementById("js-dashboard-chart").getContext("2d")).Line(barChartData);
        } else {
            var myLine = new Chart(document.getElementById("js-dashboard-chart").getContext("2d")).Line(barChartData, {animation:false});
        }
    };

    function setChart(data) {
        // set chart
        if (data.overview.timespan - data.overview.startOffset > 1) { // if multiple day overview
            var showLabels = data.overview.timespan <= 31;
            setChartData(dailyOverview.slice(data.overview.startOffset,data.overview.timespan), showLabels)
        } else { // if single day overview
            var showLabels = true;
            setChartData(data.extra.dayData, showLabels);
        }
    }
