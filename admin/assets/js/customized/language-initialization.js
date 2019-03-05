

var lang = new LangPackage('/general/language.getPackage');
lang.cache = false;
lang.registerHandler(
    'onLoaded',
    function () {
        lang.translate();
    });

lang.load('catalog,message,button,sale', Cookie.get('lang', 'ru'), 'admin');