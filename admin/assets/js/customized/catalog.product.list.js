/**
 * catalog.product.list
 */
+function () {


    var product = new ProductController();
    product.registerHandler(
        'onComplete',
        function (method, response) {

            switch (method) {

                // Удаление
                case "delete": {
                    if (response.error_code === 0) {
                        Toastr.message(response.title, response.msg, response.type);
                        $catalog_product_list.row().draw();
                        break;
                    }
                }
            }

            if (response.error_code !== 0) {
                Toastr.message(response.title, response.msg, response.type);
            }
        });


    var $catalog_product_list = $("#catalog-product-list")
        .DataTable({

            ajax: {
                url: `/admin/method/product.getRows?lang=${LSorage.read('lang', 'ru')}`,
                type: 'GET'
            },

            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json'
            },

            columns: [
                {"data": "image"},
                {"data": "title"},
                {"data": "create_at"},
                {"data": "price"},
                {"data": "options"},
            ],
            select: true,
            processing: true,
            serverSide: true,
            searching: true,
            ordering: true,
            paging: true,
            pagingType: "first_last_numbers",


            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
            }

        }).on('xhr.dt', function (e, settings, json, xhr) {

            let button_group = $('<div class="btn-group mx-2" role="group" aria-label="Second Group">');
            $('<button type="button" data-target="edit" class="btn btn-icon btn-sm btn-outline-primary">').append($('<i class="fa fa-pencil-square-o">')).appendTo(button_group);
            $('<button type="button" data-target="delete" class="btn btn-icon btn-sm btn-outline-danger">').append($('<i class="fa fa-trash-o">')).appendTo(button_group);

            for (let i = 0, ien = json.data.length; i < ien; i++) {
                json.data[i].title = '<a href="#">%s</a>'.replace('%s', json.data[i].title);
                json.data[i].image = '<img src="%s" style="max-width: 100px">'.replace('%s', json.data[i].image);
                json.data[i].options = button_group[0].outerHTML;
            }
        }).on('preXhr.dt', function (e, settings, data) {
            data.lang = LSorage.read('lang', 'ru');
        });


    $('#catalog-product-list tbody').on('click', 'button', function () {
        console.log(`Click the button ${$(this).attr('data-target')}`);

        let data = $catalog_product_list.row($(this).parents('tr')).data();

        switch ($(this).attr('data-target')) {
            case "edit": {
                Loc.setParam('id', data.id)
                    .setParam('lang', LSorage.read('lang', 'ru'))
                    .navigate('/admin/page/product.edit');

                break;
            }

            case "delete": {
                product.delete(data.id);
                break;
            }

            default:
                console.log('no data attribute data target!');
        }
    });


    $('#content-header-btn-group').on('click', 'a', function () {

        switch ($(this).attr('data-a-target')) {

            case "product-add" : {

                Loc.navigate('/admin/page/product.new');

                break;
            }

            case "lang" : {
                $catalog_product_list.rows().draw();
            }
        }
    });


}();