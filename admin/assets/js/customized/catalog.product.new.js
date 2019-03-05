/**
 * catalog.product.new
 */
+function () {

    var product_images = [];
    var product = new ProductController();
    product.registerHandler(
        'onComplete',
        function (method, response) {
            Toastr.message(response.title, response.msg, response.type);
        });


    var product_description;

    DecoupledEditor
        .create(document.querySelector('#product-description'))
        .then(editor => {
            product_description = editor;
            const toolbarContainer = document.querySelector('#toolbar-container');

            toolbarContainer.appendChild(editor.ui.view.toolbar.element);
        })
        .catch(error => {
            console.log(error);
        });


    /**
     * A group of buttons in the "content_header_right" block
     */
    $("#content-header-btn-group").on('click', 'a', function () {
        switch ($(this).attr('data-a-target')) {

            // add product
            case "product-add": {
                let fdata = new FormData();

                fdata.append('id', Url.getParamUrl('id'));
                fdata.append('lang', Url.getParamUrl('lang', 'ru'));

                $('[data-form-target]').each(function (index, element) {
                    switch ($(this).attr("type")) {
                        case "text": {
                            fdata.append($(this).attr('data-name'), $(this).val()); // text
                            break;
                        }
                        case "number": {
                            fdata.append($(this).attr('data-name'), $(this).val()); // number
                            break;
                        }
                        case "div": {
                            fdata.append($(this).attr('data-name'), $(this).html()); // div
                            break;
                        }
                        case "select": {
                            fdata.append($(this).attr('data-name'), $(this).val()); // select
                            break;
                        }
                    }
                });

                // image
                fdata.append('image', $('#product-table-image tbody img').attr('src'));

                // images
                let images = [];
                $('#product-table-images tbody img').each(function (index, element) {
                    images.push($(element).attr('src'));
                });
                fdata.append('images', images.join());

                product.add(fdata);
                break;
            }

        }
    });


    $("#product-table-images").on('click', 'button', function () {
        switch ($(this).attr('data-target')) {
            case "image-add": {
                let $elfinder = $('<div>').attr('id', 'elfinder');
                swal({
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    content: $elfinder.get(0)
                }).then((result) => {
                    if (result) {
                        if (product_images.length > 0) {
                            for (let i = 0; i < product_images.length; i++) {
                                addImage(product_images[i])
                            }
                        }
                    }


                });

                elfinderInit();
                break;
            }

            case "image-delete": {
                $(this).parent().parent().parent().remove();
            }


        }
    });


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

    /**
     * Выбор главного изображения
     */
    $('#product-table-image tbody').on('click', 'button', function () {
        switch ($(this).attr('data-target')) {
            case "image-set": {
                let $elfinder = $('<div>').attr('id', 'elfinder');

                swal({
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    content: $elfinder.get(0)
                }).then((result) => {
                    if (result) {
                        if (product_images.length > 0) {
                            $('#product-table-image tbody img').attr('src', product_images[product_images.length - 1]);
                        }
                    }


                });

                elfinderInit();
            }
        }
    });



    function elfinderInit() {
        $('.swal-modal')
            .css('width', '80%');

        $('#elfinder').elfinder(
            {
                cssAutoLoad: false,
                baseUrl: './',
                url: '/admin/data/file.manager.connector',
                lang: $('html').attr('lang'),
                height: $(window).height() - 120,
                debug: false,

                handlers: {

                    open: function (event) {
                        localStorage.setItem('elfinder_path', Url.getPath(event.data.options.url))
                    },

                    select: function (event, elfinderInstance) {
                        if (event.data.selected.length > 0) {
                            let items = event.data.selected;


                            product_images = [];

                            for (let key in items) {
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


}();