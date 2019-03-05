+function () {

    /**
     * @type {CategoryController}
     */
    const category = new CategoryController();
    category.registerHandler(
        'onComplete',
        function (method, response) {
            switch (method) {

                case "delete": {
                    Toastr.message(response.title, response.msg, response.type);
                    break;
                }

                default: {
                    Toastr.message(response.title, response.msg, response.type);
                }
            }

            $catalog_category_list.row().draw();
        });


    var category_description;
    DecoupledEditor
        .create(document.querySelector('#category-description'))
        .then(editor => {
            category_description = editor;
            const toolbarContainer = document.querySelector('#toolbar-container');

            toolbarContainer.appendChild(editor.ui.view.toolbar.element);
        })
        .catch(error => {
            console.log(error);
        });


    var $catalog_category_list = $("#sale-order-list")
        .DataTable({
            ajax: {
                url: `/admin/method/category.getAll?lang=${LSorage.read('lang', 'ru')}`,
                type: 'GET'
            },

            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json'
            },

            columns: [
                {"data": "category"},
                {"data": "options"}
            ],
            select: false,
            processing: true,
            serverSide: true,
            sort: true,
            searching: false,
            ordering: true,
            paging: true,
            pagingType: "first_last_numbers"
        }).on('xhr.dt', function (e, settings, json) {

            let button_group = $('<div class="btn-group mx-2" role="group" aria-label="Second Group">');
            $('<button type="button" data-btn-target="edit" class="btn btn-sm btn-icon btn-outline-primary">').append($('<i class="fa fa-pencil-square-o">')).appendTo(button_group);
            $('<button type="button" data-btn-target="delete" class="btn btn-sm btn-icon btn-outline-danger">').append($('<i class="fa fa-trash-o">')).appendTo(button_group);

            /** @namespace e.parent_id */
            json.data.forEach((e, i, a) => {
                if (e.parent_id !== 0) {
                    e.category = `${typeof a.find(el => el.id === e.parent_id) !== 'undefined' ? a.find(el => el.id === e.parent_id).category: ''} ❖ ${e.category}`;
                }
            });

            function loadParent(id) {
                Http.param_get()
            }

            // e.category = `${a.find(el => el.id === e.parent_id).category} ❖ ${e.category}`;

            for (let i = 0, ien = json.data.length; i < ien; i++) {
                json.data[i].options = button_group[0].outerHTML;
            }
        }).on('preXhr.dt', function (e, settings, data) {
            data.lang = LSorage.read('lang', 'ru');
        });

    /**
     * DataTable events
     */
    $('#sale-order-list tbody').on('click', 'button', function () {

        let data = $catalog_category_list.row($(this).parents('tr')).data();

        switch ($(this).attr('data-btn-target')) {

            case "edit": {
                $('#catalog-category-edit-modal')
                    .on('show.bs.modal', function () {
                        Http.get(`/admin/method/category.getRow?id=${data.id}&lang=${LSorage.read('lang', 'ru')}`, function (json) {

                            /** @namespace json.error_code */
                            /** @namespace json.data.id */
                            /** @namespace json.data.parent_id */
                            /** @namespace json.data.title */
                            /** @namespace json.data.description */
                            /** @namespace json.data.target */
                            /** @namespace json.data.parent.title */

                            $('#category-id').val(json.data.id);
                            $('#category-parent_id').val(json.data.parent_id);
                            $('#catalog-category-parent').val(!isNull(json.data.parent) ? json.data.parent.title : '');
                            $('#category-title').val(json.data.title);
                            $('#category-lang').val(LSorage.read('lang', 'ru'));
                            $('#category-target').val(json.data.target);
                            category_description.setData(json.data.description);
                        });
                    })
                    .modal('show')
                    .off('show.bs.modal');


                break;
            }

            case "delete": {
                category.delete(data.id);
                break;
            }

            default:
                console.log('no data attribute data target!');
        }
    });


    /**
     * A group of buttons in the "content_header_right" block
     */
    $('#content-header-btn-group').on('click', 'a', function () {

        switch ($(this).attr('data-a-target')) {

            case "category-add": {
                $('#catalog-category-edit-modal').modal('show');
                break;
            }

            case "category-refresh": {
                $catalog_category_list.rows().draw();
                break;
            }

            case "lang" : {
                $catalog_category_list.rows().draw();
                break;
            }

            default:
                console.log('no data attribute data target!');
        }
    });


    /**
     * Category saved.
     */
    $("#catalog-category-save").on('click', function () {
        let fdata = new FormData();

        $('[data-form-target]').each(function () {
            switch ($(this).attr("type")) {

                case "text": {
                    // noinspection JSCheckFunctionSignatures
                    fdata.append($(this).attr('data-name'), $(this).val().trim()); // text
                    $(this).val('');
                    break;
                }

                case "number": {
                    // noinspection JSCheckFunctionSignatures
                    fdata.append($(this).attr('data-name'), $(this).val()); // number
                    $(this).val(0);
                    break;
                }

                case "div": {
                    // noinspection JSCheckFunctionSignatures
                    fdata.append($(this).attr('data-name'), $(this).html()); // div
                    break;
                }

                case "select": {
                    // noinspection JSCheckFunctionSignatures
                    fdata.append($(this).attr('data-name'), $(this).val()); // select
                }
            }
        });


        function addAndUpdate(element) {
            if ($(element).val() > -1) {
                fdata.append('id', $(element).val());
                category.update(fdata);
                $(element).val(-1);
            } else {
                category.add(fdata);
            }
        }

        addAndUpdate($("#category-id"));


        category_description.setData('');
        $('#catalog-category-parent').val('');
    });


    /**
     * Autocomplete
     * @link https://github.com/devbridge/jQuery-Autocomplete
     */
    $('#catalog-category-parent').autocomplete({

        deferRequestBy: 200,
        noCache: true,
        minChars: 1,
        preventBadQueries: true,
        preserveInput: false,
        showNoSuggestionNotice: true,
        lookup: function (query, done) {
            let settings = {
                "async": true,
                "crossDomain": true,
                "url": `/admin/method/category.lookup?q=${encodeURIComponent(query)}`,
                "method": "GET",
                "headers": {
                    "cache-control": "no-cache"
                }
            };

            let result = {
                suggestions: []
            };

            $.ajax(settings).done(function (response) {
                /** @namespace response.error_code */
                if (response.error_code === 0) {
                    for (let i = 0; i < response.data.length; i++) {
                        result.suggestions.push(
                            {
                                "value": response.data[i].title,
                                "data": response.data[i].id
                            }
                        )
                    }
                }
                done(result);
            });


        },


        onSearchStart: function (params) {
            console.log(params);
        },

        transformResult: function (response) {
            /** @namespace response.myData */
            return {
                suggestions: $.map(response.myData, function (dataItem) {
                    return {value: dataItem.valueField, data: dataItem.dataField};
                })
            };
        },

        onSelect: function (suggestion) {
            $('#category-parent_id').val(suggestion.data);
        }
    })
        .on('keyup', function () {
            if ($(this).val().trim() === "") {
                $('#category-parent_id').val(0);
            }
        });


    /**
     * @param val
     * @returns {boolean}
     */
    function isNull(val) {
        return val == null;
    }

}();