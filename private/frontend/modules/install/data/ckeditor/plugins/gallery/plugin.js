/**
 * Created by Pavel on 29.7.2015.
 */

CKEDITOR.plugins.add('gallery', {
    icons: 'gallery',
    init: function( editor ) {
        editor.addCommand('gallery',
            new CKEDITOR.dialogCommand('galleryDialog')
        );
        editor.ui.addButton('Gallery', {
            label: 'Vložit galerii',
            command: 'gallery',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add('galleryDialog',
            this.path + 'dialogs/gallery.js'
        );
    }
});
