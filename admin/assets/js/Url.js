Url = {
    /**
     * @param key
     * @param def
     */
    getParamUrl: function (key, def = false) {
        let href = location.href;
        let url = new URL(href);
        return url.searchParams.has(key) ? url.searchParams.get(key) : def;
    },


    /**
     * @param key
     * @param value
     */
    setParamUrl: function (key, value) {
        let href = location.href;
        let url = new URL(href);
        url.searchParams.set(key, value);
        history.pushState("", "", url);
    },

    /**
     * @param uri
     */
    getPath: function(uri){
        let url = new URL(window.location.protocol + uri);
        return url.pathname;
    },


    /**
     * @param key
     */
    removeParamUrl: function (key) {
        let href = location.href;
        let url = new URL(href);
        url.searchParams.delete(key);
        history.pushState("", "", url);
    },


    /**
     * @param key
     * @returns {boolean}
     */
    hasParamUrl: function (key) {
        let href = location.href;
        let url = new URL(href);
        return url.searchParams.has(key);
    }
};