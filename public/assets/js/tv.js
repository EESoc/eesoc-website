$(function() {
    var $theTime = $('#the-time');
    var $theDate = $('#the-date');

    var dateTimeUpdater = function() {
        var currentMoment = moment();
        $theTime.text(currentMoment.format('h:mm:ss a'));
        $theDate.text(currentMoment.format('dddd, MMMM Do YYYY'));
    };

    var weatherUpdater = function() {
        $.simpleWeather({
            location: 'SW7 2AZ, London',
            woeid: '26363068',
            unit: 'c',
            success: function(weather) {
                html = '<h1>';
                html += '<i class="icon-' + weather.code + '"></i> ' + weather.temp + '&deg;' + weather.units.temp + ' ';
                html += '<small>' + weather.currently + '</small>';
                html += '</h1>';
                html += '<h4><ul class="list-inline">';
                html += '<li>Last updated: ' + moment(weather.updated).format('h:mm:ss a') + '</li>';
                html += '</ul></h4>';

                $("#weather").html(html);
            },
            error: function(error) {
                $("#weather").html('<p>' + error + '</p>');
            }
        });
    }

    dateTimeUpdater();
    weatherUpdater();
    setInterval(dateTimeUpdater, 1000); // every 1 second
    setInterval(weatherUpdater, 1000 * 60 * 60); // every 1 hour
});