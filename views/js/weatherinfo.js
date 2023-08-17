$(document).ready(function() {
    var paragraphs = $(".weather-data");
    var index = 0;

    function showNext() {
        if (index < paragraphs.length) {
            paragraphs.eq(index).fadeIn(1000, function() {
                $(this).delay(2000).fadeOut(1000, function() {
                    index++;
                    showNext();
                });
            });
        } else {
            // Una vez se hayan mostrado todos los párrafos, reiniciar el ciclo
            index = 0;
            showNext();
        }
    }

    showNext();
});