
import('');
+function ($) {

    window.storage = {};
    window.elfinder = {
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

                    for (let key in items) {
                        let item = items[key];
                        let parsedWordArray = CryptoJS.enc.Base64.parse(item.substr(3, item.length));
                        // product_images.push((localStorage.getItem('elfinder_path') + parsedWordArray.toString(CryptoJS.enc.Utf8)).replace(/\\/g, "/"));
                    }
                }
            },
        }
    }



}(JQuery);