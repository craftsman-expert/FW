
class Loc {

    /**
     * @param key
     * @param value
     * @returns {Loc}
     */
    static setParam(key, value) {

        if (typeof this._params === "undefined")
            this._params = [];

        this._params.push({key, value});
        return this;
    }


    /**
     * @param uri
     */
    static navigate(uri) {
        let url = new URL(location.origin);
        url.pathname = uri;

        if (typeof this._params !== "undefined"){
            this._params.forEach(function (v, i, a) {
                url.searchParams.set(v.key, v.value)
            });
        }

        location.href = url.href;
    }


}