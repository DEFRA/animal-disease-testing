var htmlTemplate = {

    injectRow: function (rowData, htmlReferenceElement) {
        var newHtmlElement = htmlReferenceElement.clone();
        newHtmlElement.show();

        var uniqueEle = Math.floor(Math.random() * 1000000) + 1;

        $.each(rowData, function (attributeName, attributeValue) {
            newHtmlElement.find('.JSON_' + attributeName).each(function () {
                var columnElement = $(this);
                columnElement.attr('id', attributeName + uniqueEle);
                columnElement.attr('row_id', uniqueEle);
                if (columnElement.is('input')) {
                    columnElement.val(attributeValue);
                } else {
                    columnElement.html(attributeValue);
                }
            });
        });

        return newHtmlElement;
    }

}