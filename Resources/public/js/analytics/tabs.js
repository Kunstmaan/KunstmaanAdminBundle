
    $(document).ready(function() {
        // Tab switcher
        $(".db-tabs__controller").click(function(){
            var id = $(this).attr('id');
            var url = $(this).attr('path');

            $.post(url,{
                overviewId:id
            }, function(data) {
                if(data.responseCode==200) {
                    $('.db-tabs__item').removeClass('db-tabs__item--active');
                    $('#tab'+id).addClass('db-tabs__item--active');

                    $('.db-content').fadeOut(100, function(){
                        setHeader(data);
                        setTraffic(data);
                        setReferrals(data);
                        setSearches(data);
                        setChart(data);
                        $('.db-content').fadeIn(400, function() {
                            initChart();
                        });
                    });

                }
           });
       });
    });

