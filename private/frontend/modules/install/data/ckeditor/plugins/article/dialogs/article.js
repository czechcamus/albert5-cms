/**
 * Created by Pavel on 28.9.2015.
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
var JSONArticleItems = $.getValues('/admin/article/article-items');
var articleItems = [['-- žádný článek --', 0]];
Object.getOwnPropertyNames(JSONArticleItems).forEach(function(val) {
    articleItems.push([val, JSONArticleItems[val]]);
});

CKEDITOR.dialog.add(
    'articleDialog', function( editor ) {
        return {
            title: 'Vložení upoutávky na článek',
            minWidth: 400,
            minHeight: 200,
            contents: [
                {
                    id: 'tab-basic',
                    label: 'Základní nastavení',
                    elements: [
                        {
                            type: 'select',
                            id: 'article_id',
                            label: 'Vyberte článek',
                            items: articleItems
                        },
                        {
                            type: 'select',
                            id: 'display_type',
                            label: 'Vyberte způsob zobrazení upoutávky',
                            items: [ [ 'Normální' ], [ 'Hlavní' ] ],
                            'default': 'Normální'
                        }
                    ]
                }
            ],
            onOk: function() {
                var dialog = this;
                var articleId = dialog.getValueOf('tab-basic', 'article_id');
                var displayType = dialog.getValueOf('tab-basic', 'display_type');

                if (articleId !== '') {
                    displayType = displayType == 'Normální' ? 'normal' : 'main';
                    var articleCode = '[article="' + articleId + '" articleType="' + displayType + '"]';
                    editor.insertText(articleCode);
                }
            }
        };
    }
);
