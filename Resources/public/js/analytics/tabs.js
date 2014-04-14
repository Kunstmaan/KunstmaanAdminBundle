    $(document).ready(function() {
        // show first tab
        // switchTab($('.db-tabs > li:nth-child(3) > a').attr('id'), $('.db-tabs > li:nth-child(3) > a').attr('path'));

        // Tab switcher
        $(".db-tabs__controller").click(function(){
            var id = $(this).attr('id');
            var url = $(this).attr('path');
            switchTab(id, url);
        });

        function switchTab(id, url) {
            $.get(url, function(data) {
                if(data.responseCode==200) {
                    $('.db-tabs__item').removeClass('db-tabs__item--active');
                    $('#tab'+id).addClass('db-tabs__item--active');

                    $('.db-content').fadeOut(100, function(){
                        // add functions here to add component behaviour
                        // these functions are declared in a per-template js file (public/js/analytics/)
                        setHeader(data);
                        setTraffic(data);
                        setReferrals(data);
                        setSearches(data);
                        setChart(data);
                        setGoals(data);
                        resetGoalChart();
                        $('.db-content').fadeIn(400, function() {
                            initChart();
                        });
                    });
                }
            });
        }


        var updateButtonText = $('#updateButton').html();
        var updating = false;

        $('#updateButton').mouseenter(function() {
            if (!updating) {
                $('#updateButton').html($('#updateButton').attr('data-update-text'));
                $('#updateButton').attr('style', 'font-weight:bold;');
            }
        }).mouseleave(function() {
            if (!updating) {
                $('#updateButton').html(updateButtonText);
                $('#updateButton').attr('style', 'font-weight:normal;');
            }
        }).click(function() {
            if (!updating) {
                updating = true;
                $('#updateButton').html($('#updateButton').attr('data-updating-text'));
                $.get(
                    "analytics/updateData",
                    function(data) {
                        updating = false;
                        location.reload(true);
                    }
                );
            }
        });
    });

