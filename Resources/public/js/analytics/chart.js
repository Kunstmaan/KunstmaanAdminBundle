   $(document).ready(function() {
        $(window).resize(function (){
            initChart();
        });
    });

    // load dashboard data
    var chartData = [];
    var chartLabels = [];

    // sets the chart data
    function setChart(data) {
        chartData = [];
        chartLabels = [];
        var increment = Math.ceil(data.overview.chartData.length / 26);
        for (var i = 0; i < data.overview.chartData.length; i+=increment) {
            chartData.push(parseInt(data.overview.chartData[i].visits));
            chartLabels.push(data.overview.chartData[i].timestamp);
        }
    }

    // sets chart width and height
    function resizeChart() {
        var chartWidth = $('#js-dashboard-chart').parent().width();
        var chartHeight = $('#js-dashboard-chart').height();
        $('#js-dashboard-chart').attr('width', chartWidth );
        $('#js-dashboard-chart').attr('height', chartHeight );
    }

    // inits the chart
    function initChart() {
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

        var myLine = new Chart(document.getElementById("js-dashboard-chart").getContext("2d")).Line(barChartData, {animation:false});
    };





