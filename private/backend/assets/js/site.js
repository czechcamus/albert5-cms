/**
 * Created by Pavel on 4.2.2015.
 */

$(function() {
    var modal = $('#modal');

    // Loads content into modal
    $(document).on('click', '.showModalButton' , function() {
        if (modal.data('bs.modal').isShown) {
            modal.find('#modalContent')
                .load($(this).attr('value'));
        } else {
            $('#modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'));
        }
        document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>' + $(this).attr('title') + '</h3>';
    });

    // Removes image thumbs from article|page form
    $(document).on('click', '.removeThumbs' , function() {
        $('.kcf-thumbs').empty();
    });

    // Shows browser button and removes image thumbnail from article|page form
    $(document).on('click', '.showBrowser' , function() {
        $('#image-browser').show();
        $('#image-thumbnail').empty();
    });

    // Animates and fade out info alert block
    $('.alert').animate({opacity: 1.0}, 3000).fadeOut('slow');
});