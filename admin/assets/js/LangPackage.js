/**
 *  Manager languages package
 */
class LangPackage {
    /**
     * @param url_api
     */
    constructor(url_api) {
        this._cache = true;
        this._api = location.origin + url_api;
        this._packages = {};
        this._handlers = {};
        this._events = [
            'onLoaded'
        ];
    }

    /**
     * @param value
     */
    set cache(value) {
        this._cache = value;
    }

    /**
     * @returns {*}
     */
    get self() {
        return this._self;
    }

    set self(value) {
        this._self = value;
    }

    get events() {
        return this._events;
    }

    set events(value) {
        this._events = value;
    }

    get handlers() {
        return this._handlers;
    }

    set handlers(value) {
        this._handlers = value;
    }

    /**
     * @returns {*|null}
     */
    get packages() {
        return this._packages;
    }

    /**
     * @param value
     */
    set packages(value) {
        this._packages = value;
    }

    /**
     * @param packages
     * @param lang
     * @param env
     */
    load(packages, lang, env = 'Cms') {

        let settings = {
            "async": "async",
            "crossDomain": true,
            "url": `${this._api}?env=${env}&packages=${packages}&lang=${lang}`,
            "method": "GET",
            "cache": this._cache,
            "headers": {
                "cache-control": "public"
            }
        };


        let _this = this;
        $.ajax(settings).done(function (data) {
            _this.packages = data;
            _this.onLoaded();
        });
    }


    /**
     * @param package_name
     * @param key
     */
    translate(package_name, key) {

        let item = this.packages.items[package_name];
        if (typeof item !== "undefined"){
            return this.packages.items[package_name].translated_content[key];
        }
        return 'Language pack file not found!';
    }


    /**
     * Event loaded
     */
    onLoaded() {
        for (let i = 0, l = this.handlers.onLoaded.length; i < l; i++) {
            this.handlers.onLoaded[i]();
        }
    };


    /**
     * @param type
     * @param func
     */
    registerHandler(type, func) {
        if (this.events.indexOf(type) === -1) throw "Invalid Event!";
        if (this.handlers[type]) {
            this.handlers[type].push(func);
        } else {
            this.handlers[type] = [func];
        }
    };


    /**
     * Find and translate content
     */
    autoTranslate() {
        let _this = this;
        $('[data-lang-key]').each(function (a, c, d) {
            let lang_package = $(this).attr('data-lang-package');
            let data_lang_key = $(this).attr('data-lang-key');

            $(this).text(_this.read(lang_package, data_lang_key))
        })
    }
}