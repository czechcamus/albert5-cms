/**
 * Created by Pavel on 29.7.2015.
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
var JSONGalleryItems = $.getValues('/admin/gallery/gallery-items');
var galleryItems = [['-- žádná galerie --', 0]];
Object.getOwnPropertyNames(JSONGalleryItems).forEach(function(val) {
    galleryItems.push([val, JSONGalleryItems[val]]);
});
var JSONDisplayItems = $.getValues('/admin/gallery/display-items');
var displayItems = Object.keys(JSONDisplayItems).map(function(i) {
    return [JSONDisplayItems[i]];
});

CKEDITOR.dialog.add(
    'galleryDialog', function( editor ) {
        return {
            title: 'Vložení galerie',
            minWidth: 400,
            minHeight: 200,
            contents: [
                {
                    id: 'tab-basic',
                    label: 'Základní nastavení',
                    elements: [
                        {
                            type: 'select',
                            id: 'gallery_id',
                            label: 'Vyberte fotogalerii',
                            items: galleryItems
                        },
                        {
                            type: 'select',
                            id: 'display_type',
                            label: 'Vyberte způsob zobrazení fotogalerie',
                            items: displayItems
                        },
                        {
                            type: 'select',
                            id: 'align_link',
                            label: 'Vyberte způsob zarovnání upoutávky (uplatní se pouze u typu box)',
                            items: [ [ 'vlevo' ], [ 'vpravo' ] ],
                            'default': 'vlevo'
                        }
                    ]
                }
            ],
            onOk: function() {
                var dialog = this;
                var galleryId = dialog.getValueOf('tab-basic', 'gallery_id');
                var displayType = dialog.getValueOf('tab-basic', 'display_type');
                var alignLink = dialog.getValueOf('tab-basic', 'align_link');

                if (galleryId !== '0') {
                    var galleryCode = '[gallery="' + galleryId + '" type="' + displayType + '"';
                    if (alignLink.trim() !== '') {
                        var alignString = (alignLink.trim() == 'vlevo') ? 'left':'right';
                        galleryCode += ' align="' + alignString + '"';
                    }
                    galleryCode += ']';
                    editor.insertText(galleryCode);
                }
            }
        };
    }
);
