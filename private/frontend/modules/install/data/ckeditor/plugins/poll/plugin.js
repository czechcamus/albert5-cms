/**
 * Created by Pavel on 23.9.2015.
 */

CKEDITOR.plugins.add('poll', {
    icons: 'poll',
    init: function( editor ) {
        editor.addCommand('poll',
            new CKEDITOR.dialogCommand('pollDialog')
        );
        editor.ui.addButton('Poll', {
            label: 'Vložit anketu',
            command: 'poll',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add('pollDialog',
            this.path + 'dialogs/poll.js'
        );
    }
});
