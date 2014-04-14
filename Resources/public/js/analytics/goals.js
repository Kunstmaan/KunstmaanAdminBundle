
    function getGoalData(goalOverview) {
        var id = goalOverview.attr('data-goal-id');
        $('.active').attr('class', '');
        $('#goal'+id).attr('class', 'active');

        $.get('analytics/getGoalGraphData/'+id, function(data) {
            setGoalChart(data);
            $('#goal_title').html(data.name);
        });
    }

    function setGoals(data) {
        $('#goalOverview').html('');
        var html = '';
        for(var i = 0; i < data.extra.goals.length; i++) {
            html    +=
                     '<li id="goal'+data.extra.goals[i]['id']+'" data-goal-id="'+data.extra.goals[i]['id']+'" onClick="getGoalData($(this))">'
                    +    '<div>'
                    +        data.extra.goals[i]['name']
                    +    '</div>'
                    +    '<span>'
                    +        data.extra.goals[i]['visits']
                    +    '</span>'
                    +'</li>';
        }

        $('#goalOverview').html(html);
    }

    var goalChartData = [];
    var goalChartLabels = [];

    // reset the chart
    function resetGoalChart() {
        goalChartData = [];
        goalChartLabels = [];
        initGoalChart();
        $('#goal_title').html('');
    }

    // sets the chart data
    function setGoalChartData(data, showLabels, isDayData) {
        goalChartData = [];
        goalChartLabels = [];
        if (data != null) {
            for (var i = 0; i < data.graphData.length; i++) {
                goalChartData.push(parseInt(data.graphData[i].visits));
                if (showLabels == true || i % 5 == 0) {
                    goalChartLabels.push(data.graphData[i].timestamp);
                } else {
                    goalChartLabels.push("");
                }
            }
        }
        initGoalChart();
    }

    // sets chart width and height
    function resizeGoalChart() {
        var chartWidth = $('#js-goal-dashboard-chart').parent().width();
        var chartHeight = $('#js-goal-dashboard-chart').height();
        $('#js-goal-dashboard-chart').attr('width', chartWidth );
        $('#js-goal-dashboard-chart').attr('height', chartHeight );
    }

    // inits the chart
    initGoalChart = function() {
        var barGoalChartData = {
            labels : goalChartLabels,
            datasets : [
                {
                    fillColor : "rgba(41, 151, 206, 0.3)",
                    strokeColor : "rgb(41, 151, 206)",
                    pointColor : "rgb(41, 151, 206)",
                    pointStrokeColor : "#fff",
                    data : goalChartData,
                    scaleShowLabels : true
                }
            ]
        };

        resizeGoalChart();
        var chart = new Chart(document.getElementById("js-goal-dashboard-chart").getContext("2d")).Line(barGoalChartData, {animation:false});
    };


    function setGoalChart(data) {
        setGoalChartData(data, data.graphData.length <= 31)
    }
