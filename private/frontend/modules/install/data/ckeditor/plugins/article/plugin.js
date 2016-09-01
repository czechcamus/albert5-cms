/**
 * Created by Pavel on 28.9.2015.
 */

CKEDITOR.plugins.add('article', {
    icons: 'article',
    init: function( editor ) {
        editor.addCommand('article',
            new CKEDITOR.dialogCommand('articleDialog')
        );
        editor.ui.addButton('Article', {
            label: 'Vložit upoutávku na článek',
            command: 'article',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add('articleDialog',
            this.path + 'dialogs/article.js'
        );
    }
});
