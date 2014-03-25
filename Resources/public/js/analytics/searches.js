
    function setSearches(data) {
        if (data.overview.topSearchFirst != 0) {
            $('#data_top_search_no_data').html('');

            $('#data_overview_top_search_first').html(data.overview.topSearchFirst);
            $('#data_overview_top_search_first_value').html(data.overview.topSearchFirstValue);

            if (data.overview.topSearchSecond != 0) {
                $('#data_overview_top_search_second').html(data.overview.topSearchSecond);
                $('#data_overview_top_search_second_value').html(data.overview.topSearchSecondValue);
            } else {
                $('#data_overview_top_search_second').html('');
                $('#data_overview_top_search_second_value').html('');
            }

            if (data.overview.topSearchThird != 0) {
                $('#data_overview_top_search_third').html(data.overview.topSearchThird);
                $('#data_overview_top_search_third_value').html(data.overview.topSearchThirdValue);
            } else {
                $('#data_overview_top_search_third').html('');
                $('#data_overview_top_search_third_value').html('');
            }
        } else {
            $('#data_top_search_no_data').html('No data available for Top Search Terms');

            $('#data_overview_top_search_first').html('');
            $('#data_overview_top_search_first_value').html('');

            $('#data_overview_top_search_second').html('');
            $('#data_overview_top_search_second_value').html('');

            $('#data_overview_top_search_third').html('');
            $('#data_overview_top_search_third_value').html('');
        }
    }
