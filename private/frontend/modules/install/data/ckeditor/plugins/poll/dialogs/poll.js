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
var JSONPollItems = $.getValues('/admin/poll/poll-items');
var pollItems = [['-- žádná anketa --', 0]];
Object.getOwnPropertyNames(JSONPollItems).forEach(function(val) {
    pollItems.push([val, JSONPollItems[val]]);
});
var JSONDisplayItems = $.getValues('/admin/poll/display-items');
var displayItems = Object.keys(JSONDisplayItems).map(function(i) {
    return [JSONDisplayItems[i]];
});

CKEDITOR.dialog.add(
    'pollDialog', function( editor ) {
        return {
            title: 'Vložení ankety',
            minWidth: 400,
            minHeight: 200,
            contents: [
                {
                    id: 'tab-basic',
                    label: 'Základní nastavení',
                    elements: [
                        {
                            type: 'select',
                            id: 'poll_id',
                            label: 'Vyberte anketu',
                            items: pollItems
                        },
                        {
                            type: 'select',
                            id: 'display_type',
                            label: 'Vyberte způsob zobrazení anketních výsledků',
                            items: displayItems
                        },
                        {
                            type: 'select',
                            id: 'column_width',
                            label: 'Vyberte šířku zobrazení ankety',
                            items: [ [ '100%' ], [ '50%' ] ],
                            'default': '100%'
                        }
                    ]
                }
            ],
            onOk: function() {
                var dialog = this;
                var pollId = dialog.getValueOf('tab-basic', 'poll_id');
                var displayType = dialog.getValueOf('tab-basic', 'display_type');
                var columnWidth = dialog.getValueOf('tab-basic', 'column_width');

                if (pollId !== '') {
                    displayType = displayType == 'Sloupce' ? 'BarChart' : 'PieChart';
                    columnWidth = columnWidth == '100%' ? 's12' : 's6';
                    var pollCode = '[poll="' + pollId + '" chartType="' + displayType + '" colWidth="' + columnWidth + '"]';
                    editor.insertText(pollCode);
                }
            }
        };
    }
);
