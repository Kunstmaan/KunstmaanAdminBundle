
    function setReferrals(data) {
        if (data.overview.topReferralFirst != 0) {
            $('#data_top_referral_no_data').html('');

            $('#data_overview_top_referral_first').html(data.overview.topReferralFirst);
            $('#data_overview_top_referral_first_value').html(data.overview.topReferralFirstValue);

            if (data.overview.topReferralSecond != 0) {
                $('#data_overview_top_referral_second').html(data.overview.topReferralSecond);
                $('#data_overview_top_referral_second_value').html(data.overview.topReferralSecondValue);
            } else {
                $('#data_overview_top_referral_second').html('');
                $('#data_overview_top_referral_second_value').html('');
            }

            if (data.overview.topReferralThird != 0) {
                $('#data_overview_top_referral_third').html(data.overview.topReferralThird);
                $('#data_overview_top_referral_third_value').html(data.overview.topReferralThirdValue);
            } else {
                $('#data_overview_top_referral_third').html('');
                $('#data_overview_top_referral_third_value').html('');
            }
        } else {
            $('#data_top_referral_no_data').html('No data available for Top Referrals');

            $('#data_overview_top_referral_first').html('');
            $('#data_overview_top_referral_first_value').html('');

            $('#data_overview_top_referral_second').html('');
            $('#data_overview_top_referral_second_value').html('');

            $('#data_overview_top_referral_third').html('');
            $('#data_overview_top_referral_third_value').html('');
        }
    }
