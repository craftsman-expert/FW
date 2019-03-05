/**
 * Cookie manager
 */
class Cookie {

    /**
     * @param name
     * @param value
     * @param options
     */
    static set(name, value, options){
        options = options || {}; //по умолчанию нет параметров (допустимые: expires, domain, secure, path)
        let expires = options.expires;

        if (typeof expires === "number" && expires) { //если указано время жизни, и это число
            let d = new Date();
            d.setTime(d.getTime() + expires * 1000); //expires в секундах
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);
        let data = name + "=" + value; //строка в формате cookie имеет вид "имя_куки=значение"

        for (let propName in options) {   //дописываем параметры кук (domain, secure, path)
            data += "; " + propName;
            let propValue = options[propName];
            if (propValue !== true) {
                data += "=" + propValue;
            }
        }

        document.cookie = data; //сохраняем куку
    }

    /**
     * @param name
     * @param def
     * @returns {string}
     */
    static get(name, def) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : def;
    }


    /**
     * @param name
     * @returns {boolean}
     */
    static has(name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? true : false;
    }

}