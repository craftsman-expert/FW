/**
 * @type {{read: LSorage.read, write: LSorage.write}}
 */
LSorage = {

    /**
     * @param key
     * @param def
     * @returns {*}
     */
    read: function (key, def = false) {
        if (localStorage.hasOwnProperty(key)){
            return localStorage.getItem(key);
        }
        return def;
    },

    /**
     * @param key
     * @param value
     */
    write: function (key, value) {
        localStorage.setItem(key, value)
    }
};