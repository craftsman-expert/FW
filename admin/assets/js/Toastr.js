/**
 * @type {{message: Toastr.message}}
 */

Toastr = {
    options: {
        timeOut: 3000
    },

    onHidden: null,
    onShown: null,

    message: function (title, message, type, options) {

        toastr.options.closeMethod = 'fadeOut';
        toastr.options.closeDuration = 300;
        toastr.options.closeEasing = 'swing';

        toastr.options.onShown = function () {
            if (Toastr.onShown !== null)
                Toastr.onShown();
        };

        toastr.options.onHidden = function () {
            if (Toastr.onHidden !== null)
                Toastr.onHidden();
        };

        if (typeof options === "undefined"){
            options = Toastr.options;
        }

        switch (type) {
            case "success": {
                toastr.success(message, title, options);
                break;
            }

            case "error": {
                toastr.error(message, title, options);
                break;
            }

            case "warning": {
                toastr.warning(message, title, options);
                break;
            }

            case "info": {
                toastr.info(message, title, options);
                break;
            }
        }
    }
};

