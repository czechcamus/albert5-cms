/**
 * Created by Pavel on 23.9.2015.
 */

CKEDITOR.plugins.add('youtube', {
    icons: 'youtube',
    init: function( editor ) {
        editor.addCommand('youtube',
            new CKEDITOR.dialogCommand('youtubeDialog')
        );
        editor.ui.addButton('Youtube', {
            label: 'Vložit youtube video',
            command: 'youtube',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add('youtubeDialog',
            this.path + 'dialogs/youtube.js'
        );
    }
});
