/**
 * Created by Pavel on 8.9.2015.
 */

// jQuery functions
( function($) {
    $(document).ready(function() {
        $("#search-btn").find("i").click(function() {
            $("#search-form-box").toggle("slow");
        });

        $('.modal-trigger').leanModal();
        $('select').material_select();
        $('.button-collapse').sideNav();
    });
} )( jQuery );