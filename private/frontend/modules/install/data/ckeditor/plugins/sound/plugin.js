/**
 * Created by Pavel on 23.9.2015.
 */

CKEDITOR.plugins.add('sound', {
    icons: 'sound',
    init: function( editor ) {
        editor.addCommand('sound',
            new CKEDITOR.dialogCommand('soundDialog')
        );
        editor.ui.addButton('Sound', {
            label: 'Vložit zvuk',
            command: 'sound',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add('soundDialog',
            this.path + 'dialogs/sound.js'
        );
    }
});
