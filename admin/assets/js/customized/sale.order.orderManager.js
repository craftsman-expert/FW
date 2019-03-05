
+function () {

    const order = new OrderController();
    order.registerHandler(
        'onComplete',
        function (method, response) {

            switch (method) {

                case "delete": {
                    Toastr.message(response.title, response.msg, response.type);
                    $sale_order_list.row().draw(true);
                    break;
                }
            }

            if (response.error_code !== 0) {
                Toastr.message(response.title, response.msg, response.type);
            }
        });

    /**
     * @type {jQuery}
     */
    var $sale_order_list = $("#sale-order-list")
        .DataTable({

            ajax: {
                url: '/admin/method/order.getRows',
                type: 'GET'
            },

            columns: [
                {"data": "id"},
                {"data": "client"},
                {"data": "status"},
                {"data": "amount"},
                {"data": "create_at"},
                {"data": "update_at"},
                {"data": "options"}
            ],


            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json'
            },

            select: false,
            processing: true,
            serverSide: true,
            sort: true,
            searching: true,
            ordering: true,
            paging: true,
            pagingType: "first_last_numbers"

        }).on('xhr.dt', function (e, settings, json, xhr) {

            let button_group = $('<div class="btn-group mx-2" role="group" aria-label="Second Group">');
            $('<button type="button" data-btn-target="edit" class="btn btn-sm btn-icon btn-outline-primary">').append($('<i class="fa fa-eye">')).appendTo(button_group);
            $('<button type="button" data-btn-target="delete" class="btn btn-sm btn-icon btn-outline-danger">').append($('<i class="fa fa-trash-o">')).appendTo(button_group);

            let getStatus = function (status) {
                switch (status) {
                    case "new": return `<span class="text-highlight yellow">${status}</span>`;
                    case "process": return `<span class="text-highlight warning">${status}</span>`;
                    case "complete": return `<span class="text-highlight success">${status}</span>`;
                }
            };

            for (let i = 0, ien = json.data.length; i < ien; i++) {
                json.data[i].status = getStatus(json.data[i].status);
                json.data[i].options = button_group[0].outerHTML;
            }


        });


    /**
     *  События кнопок внутри таблицы
     */
    $('#sale-order-list tbody').on('click', 'button', function () {

        let data = $sale_order_list.row($(this).parents('tr')).data();

        switch ($(this).attr('data-btn-target')) {

            case "edit": {
                open(`/admin/page/order.orderDetails?id=${data.id}`, '_self')
                break;
            }

            case "delete": {
                swal({

                    text: lang.translate('sale', 'request_delete_order'),
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: lang.translate('button', 'btn_no'),
                            value: null,
                            visible: true,
                            className: "",
                            closeModal: true,
                        },
                        confirm: {
                            text: lang.translate('button', 'btn_yes'),
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }
                }).then((isConfirm) => {
                        if (isConfirm) {
                            order.delete(data.id);
                        }
                    });


                break;
            }

            default:
                console.log('no data attribute data target!');
        }
    });
}();