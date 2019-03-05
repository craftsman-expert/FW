/**
 * @constructor
 */
function OrderController() {
    var self = this;
    var baseUrl = location.origin;
    this.handlers = {};
    this.events = [
        'onComplete'
    ];


    /**
     * @param id
     */
    this.delete = function (id) {
        let url = new URL(baseUrl + '/admin/method/order.delete');
        url.searchParams.set('id', id);

        Http.get(url, function (response) {
            self.onComplete('delete', response);
        })
    };


    /**
     * =================================================
     * Events
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