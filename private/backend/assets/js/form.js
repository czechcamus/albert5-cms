/**
 * Created by Pavel on 18.2.2015.
 */
$(function() {
    $(document).ready(function() {
        var boxesSelector = $('#modal-boxes');

        if (boxesSelector.attr('data-main') == 1) {
            boxesSelector.find('input:eq(0), input:eq(1)').on('click', function() {
                return false;
            });
        }

        // If main checkbox is checked, checks active also
        boxesSelector.find('input:eq(0)').on('change', function() {
            if ($(this).is(':checked')) {
                boxesSelector.find('input:eq(1)').prop('checked', true);
            }
        });

        // If active checkbox is unchecked, unchecks main also
        //noinspection JSJQueryEfficiency
        boxesSelector.find('input:eq(1)').on('change', function() {
            if (!$(this).is(':checked')) {
                boxesSelector.find('input:eq(0)').prop('checked', false);
            }
        });

        // Adds or removes answer fields of poll
        var max_fields      = 10; //maximum input boxes allowed
        var wrapper         = $(".input_fields_wrap"); //Fields wrapper
        var add_button      = $(".add_field_button"); //Add button ID

        var x = wrapper.attr('data-fields'); //initial text box count
        add_button.click(function(e){ //on add input button click
            e.preventDefault();
            if(x < max_fields){ //max input box allowed
                x++; //text box increment
                wrapper.append('<div class="input-field-row"><label class="control-label col-sm-2"><a href="#" class="remove_field"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> ' + x + '.</label><div class="col-sm-9"><input type="text" name="PollForm[answers][]" class="form-control answer-field" /></div></div>'); //add input box
            }
        });

        wrapper.on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault();
            $(this).parents('.input-field-row').remove();
            x--;
        });

        // Checks/uncheks all checkboxes on the page
        var selectAll = $(".selectAll");

        selectAll.on('change', function() {
            if ($(this).is(':checked')) {
                $('[type="checkbox"]').prop('checked', true);
            } else {
                $('[type="checkbox"]').prop('checked', false);
            }
        });
    });
});