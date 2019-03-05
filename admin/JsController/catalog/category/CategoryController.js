
/**
 * @constructor
 */
function CategoryController() {
    var self = this;
    var baseUrl = location.origin;
    this.handlers = {};
    this.events = [
        'onComplete'
    ];



    /**
     * @param form
     */
    this.add = function (form) {
        let url = new URL(baseUrl + '/admin/method/category.add');

        Http.post(url, form, function (response) {
            self.onComplete('add', response);
        })
    };



    /**
     * Category update
     * @param form
     */
    this.update = function (form) {
        let url = new URL(baseUrl + '/admin/method/category.update');

        Http.post(url, form, function (response) {
            self.onComplete('update', response);
        })
    };



    /**
     * @param id
     * @param lang
     */
    this.delete = function (id, lang = false) {
        let url = new URL(baseUrl + '/admin/method/category.delete');
        url.searchParams.set('id', id);

        if (lang){
            url.searchParams.set('lang', lang);
        }

        Http.get(url, function (response) {
            self.onComplete('delete', response);
        })
    };




    /**
     * =================================================
     * События
     * =================================================
     */

    /**
     * @param method
     * @param response
     */
    this.onComplete = function (method, response) {
        for (let i = 0, l = this.handlers.onComplete.length; i < l; i++) {
            this.handlers.onComplete[i](method, response);
        }
    };


    /**
     * @param type
     * @param func
     */
    this.registerHandler = function (type, func) {
        if (this.events.indexOf(type) === -1) throw "Invalid Event!";
        if (this.handlers[type]) {
            this.handlers[type].push(func);
        } else {
            this.handlers[type] = [func];
        }
    };

}
