/**
 * Created by Pavel on 8.9.2015.
 */

// jQuery functions
( function($) {
    $(document).ready(function() {
        var owl = $("#owl-image-links");

        $('body').addClass('loaded');

        $("#search-btn").find("i").click(function() {
            $("#search-form-box").toggle("slow");
        });
        
        $('.parallax').parallax();

        var options = [
            {selector: '.container1', offset: 100, callback: function() {
                $('.container2').addClass('animated fadeInLeftBig');
            } }
        ];
        Materialize.scrollFire(options);

        owl.owlCarousel({
            autoPlay: 10000, //Set AutoPlay to 10 seconds
            items : 6,
            itemsDesktop : [1000,4],
            itemsDesktopSmall : [700,3],
            itemsTablet : [400, 2],
            itemsMobile : false
        });
        
        $('.modal-trigger').leanModal();
        $('select').material_select();
        $('.button-collapse').sideNav();
    });
} )( jQuery );