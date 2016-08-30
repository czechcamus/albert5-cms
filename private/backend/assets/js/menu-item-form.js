/**
 * Created by Pavel on 16.2.2015.
 */
$(function() {
    // selector variables
    var boxesSelector = $('#menuitemform-boxes');

    // If main checkbox is checked, checks active also
    boxesSelector.find('input:eq(0)').on('change', function() {
        if ($(this).is(':checked')) {
            $('#menuitemform-boxes').find('input:eq(1)').prop('checked', true);
        }
    });

    // If active checkbox is unchecked, unchecks main also
    boxesSelector.find('input:eq(1)').on('change', function() {
        if (!$(this).is(':checked')) {
            $('#menuitemform-boxes').find('input:eq(0)').prop('checked', false);
        }
    });

    // Shows or hides link input or content and layout dropdown
    $.fn.changeForm = function(tid) {
        if (tid == 3) {
            $('#link_url').show();
            $('#link_target').show();
            $('#content_id').hide();
        } else {
            $('#link_url').hide();
            $('#link_target').hide();
            $('#content_id').show();
        }
    }
});
