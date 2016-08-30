/**
 * Created by Pavel on 27.8.2015.
 */

// jQuery functions
( function($) {
    $(document).ready(function() {
        var options = [
            {selector: '.section1', offset: 400, callback: function() {
                $('.section2').addClass('animated fadeInRightBig');
            } }
        ];
        Materialize.scrollFire(options);
    });
} )( jQuery );