/**
 * catalog.product.edit
 */
+function () {

    /** image_target */
    var image_target;
    var product_images = [];
    var product = new ProductController();
    product.registerHandler(
        'onComplete',
        function (method, response) {

            switch (method) {

                // Удаление
                case "delete": {
                    if (response.error_code === 0) {
                        Toastr.message(response.title, response.msg, response.type);
                    }
                    break;
                }

                // обновление
                case "update": {
                    if (response.error_code === 0) {
                        Toastr.message(response.title, response.msg, response.type);
                    }
                }
            }

            if (response.error_code !== 0) {
                Toastr.message(response.title, response.msg, response.type);
            }
        });


    var product_description;


    DecoupledEditor.create(document.querySelector('#product-description'))
        .then(editor => {
            product_description = editor;
            const toolbarContainer = document.querySelector('#toolbar-container');

            toolbarContainer.appendChild(
                editor.ui.view.toolbar.element
            );
        })
        .catch(error => {
            console.log(error);
        });




    /**
     * A group of buttons in the "content_header_right" block
     */
    $("#content-header-btn-group").on('click', 'a', function () {
        switch ($(this).attr('data-a-target')) {

            // update product
            case "product-update": {

                let obj = {};

                obj.id = parseInt(Url.getParamUrl('id'));
                obj.lang = LSorage.read('lang', 'ru');
                obj.title = $('#product-title').val();
                obj.description = $('#product-description').html();
                obj.category = [];
                $("#product-category option").each(function (i, e) {
                    obj.category.push(parseInt($(e).val()));
                });
                obj.article = $('#product-article').val();
                obj.price = $('#product-price').val();
                obj.old_price = $('#product-old_price').val();
                obj.quantity_stock = parseInt($('#product-quantity_stock').val());
                obj.status = parseInt($('#product-status').val());
                obj.image = $('#product-table-image tbody img').attr('src');
                obj.images = [];
                $('#product-table-images tbody img').each(function (index, element) {
                    obj.images.push($(element).attr('src'));
                });
                product.update(JSON.stringify(obj));
                break;
            }

            case "lang": {
                Loc.setParam('id', Url.getParamUrl('id'))
                    .setParam('lang', LSorage.read('lang', 'ru'))
                    .navigate('/admin/page/product.edit');
            }
        }
    });


    $("#product-table-images").on('click', 'button', function () {
        switch ($(this).attr('data-target')) {
            case "image-add": {
                $('#elfinder-modal').trigger('show', $("#product-table-images"));

                break;
            }

            case "image-delete": {
                $(this).parent().parent().parent().remove();
            }


        }
    });


    /**
     * Select main picture
     */
    $('#product-table-image tbody').on('click', 'button', function () {
        switch ($(this).attr('data-target')) {
            case "image-set": {
                $('#elfinder-modal').trigger('show', $('#product-table-image'));
            }
        }
    });


    /**
     * Modal file manager window
     */
    $('#elfinder-modal')
        .on('shown.af.modal', function () {
            // before show modal
        })
        .on('shown.bf.modal', function () {
            // after show modal
        })
        .on('show', function (e, table) {

            switch ($(table).attr('id')) {

                case "product-table-image":
                    image_target = 'image';
                    break;
                case "product-table-images":
                    image_target = 'images';
            }

            $('#elfinder-modal').modal('show');

            $(this).off('selectedimage');
            $(this).on('selectedimage', function (e, data) {
                /**
                 * @namespace data.image_target
                 * @namespace data.images
                 */
                switch (data.image_target) {
                    case "image": {
                        $('#product-table-image tbody img').attr('src', data.images[0]);
                        break;
                    }
                    case "images": {
                        data.images.forEach(function (e, i, a) {
                            addImage(e);
                        })
                    }
                }

                $('#elfinder-modal').modal('hide');
            });

        })
        .on('click', 'button', function () {
            switch ($(this).attr('data-btn-target')) {
                case "success": {
                    // confirmation selected image
                    $(this).trigger('selectedimage', {
                        image_target: image_target,
                        images: product_images
                    })
                }
            }

        });


    /**
     * Autocomplete
     * @link https://github.com/devbridge/jQuery-Autocomplete
     */
    $('#product-category-lookup').autocomplete({

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
            $('#product-category')
                .append($('<option>')
                    .val(suggestion.data)
                    .text(suggestion.value)
                );

            $('#product-category-lookup').val('');

            let map = {};
            $('#product-category option').each(function () {
                if (map[this.value]) {
                    $(this).remove()
                }
                map[this.value] = true;
            })
        }
    });


    $('#product-category').on('dblclick', 'option', function () {
        $(this).remove();
    });

    $(document).ready(function () {
        elfunderinitialization();
    });

    function elfunderinitialization() {
        $('#elfinder').elfinder(
            {
                cssAutoLoad: false,
                baseUrl: './',
                url: '/admin/data/file.manager.connector',
                lang: 'ru',
                debug: false,
                showThreshold: 50,

                handlers: {

                    init: function (s, n) {
                    },
                    load: function () {
                        $('#elfinder').css({
                            "height": `${$(window).height() - 300}px`
                        });
                    },

                    // extract archive files on upload
                    upload: function (event, instance) {
                        let uploadedFiles = event.data.added;
                        let archives = ['application/zip', 'application/x-gzip', 'application/x-tar', 'application/x-bzip2'];
                        for (i in uploadedFiles) {
                            let file = uploadedFiles[i];
                            if (jQuery.inArray(file.mime, archives) >= 0) {
                                instance.exec('extract', file.hash);
                            }
                        }
                    },

                    open: function (event) {
                        LSorage.write('elfinder_path', Url.getPath(event.data.options.url));
                    },

                    select: function (event, instance) {
                        if (event.data.selected.length > 0) {
                            let items = event.data.selected;

                            product_images = [];

                            for (let key in items) {
                                // noinspection JSUnfilteredForInLoop
                                let item = items[key];
                                let parsedWordArray = CryptoJS.enc.Base64.parse(item.substr(3, item.length));
                                product_images.push((localStorage.getItem('elfinder_path') + parsedWordArray.toString(CryptoJS.enc.Utf8)).replace(/\\/g, "/"));
                            }
                        }

                    },
                }
            }
        );
    }


    /**
     * @param src
     */
    function addImage(src) {
        $("#product-table-images tbody")
            .append($('<tr>')
                .append($('<td class="width-200">')
                    .append($(`<img class="img-thumbnail img-fluid" src="${src}" itemprop="thumbnail" alt="">`)))
                .append($('<td>'))
                .append($('<td style="width: 1%">')
                    .append($('<div class="btn-group mx-2">')
                        .append($('<button type="button" data-target="image-delete" class="btn btn-icon btn-outline-danger"><i class="fa fa-trash-o"></i></button>'))
                    )
                )
            );
    }

}();