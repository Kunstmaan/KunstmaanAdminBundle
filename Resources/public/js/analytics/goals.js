    function setGoals(data) {
        $('#goalOverview').html('');
        var html = '';
        for(var i = 0; i < data.extra.goals.length; i++) {
            html    +=
                     '<li>'
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
