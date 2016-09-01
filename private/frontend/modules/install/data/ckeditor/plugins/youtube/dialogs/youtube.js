/**
 * Created by Pavel on 23.9.2015.
 */
CKEDITOR.dialog.add(
    'youtubeDialog', function( editor ) {
        return {
            title: 'Vložení youtube videa',
            minWidth: 400,
            minHeight: 200,
            contents: [
                {
                    id: 'tab-basic',
                    label: 'Základní nastavení',
                    elements: [
                        {
                            type: 'text',
                            id: 'youtube_id',
                            label: 'Zadejte kód youtube videa',
                            validate: CKEDITOR.dialog.validate.notEmpty( "Toto pole nesmí být prázdné." )
                        }                    ]
                }
            ],
            onOk: function() {
                var dialog = this;
                var youtubeId = dialog.getValueOf('tab-basic', 'youtube_id');

                if (youtubeId !== '0') {
                    var youtubeCode = '[youtube="' + youtubeId + '"]';
                    editor.insertText(youtubeCode);
                }
            }
        };
    }
);
