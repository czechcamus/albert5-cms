/**
 * Created by Pavel on 23.9.2015.
 */
jQuery.extend({
    getValues: function(url) {
        var result = null;
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            async: false,
            success: function(data) {
                result = data;
            }
        });
        return result;
    }
});
var JSONSoundItems = $.getValues('/admin/file/sound-items');
var soundItems = [['-- žádný zvukový soubor --', 0]];
Object.getOwnPropertyNames(JSONSoundItems).forEach(function(val) {
    soundItems.push([val, JSONSoundItems[val]]);
});

CKEDITOR.dialog.add(
    'soundDialog', function( editor ) {
        return {
            title: 'Vložení zvuku',
            minWidth: 400,
            minHeight: 200,
            contents: [
                {
                    id: 'tab-basic',
                    label: 'Základní nastavení',
                    elements: [
                        {
                            type: 'select',
                            id: 'sound_id',
                            label: 'Vyberte zvukový soubor',
                            items: soundItems
                        }
                    ]
                }
            ],
            onOk: function() {
                var dialog = this;
                var soundId = dialog.getValueOf('tab-basic', 'sound_id');

                if (soundId !== '0') {
                    var soundCode = '[sound="' + soundId + '"]';
                    editor.insertText(soundCode);
                }
            }
        };
    }
);
