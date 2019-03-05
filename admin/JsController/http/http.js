/**
 * Http
 * @type {{httpGet: Http.httpGet, httpPost: Http.httpPost}}
 */
Http = {


    /**
     * @param url
     * @param callback function
     * @param async
     */
    get: function (url, callback, async = true) {



        let settings = {
            "async": async,
            "crossDomain": true,
            "url": url,
            "method": "GET",
            "headers": {
                "cache-control": "no-cache"
            }
        };

        $.ajax(settings).done(function (response) {
            callback(response);
        });
    },


    /**
     * @param url
     * @param data mixed
     * @param callback
     */
    post: function (url, data, callback) {

        let settings = {
            "async": true,
            "crossDomain": true,
            "url": url,
            "method": "POST",
            "headers": {
                "cache-control": "no-cache"
            },
            "processData": false,
            "contentType": false,
            "mimeType": "multipart/form-data",
            "data": data,
            "dataType": "json"
        };

        $.ajax(settings).done(function (response) {
            callback(response);
        });
    }
};