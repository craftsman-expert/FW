

+function () {

    $(document).ready(function () {
        // TODO: bla-bla
    });

    $('#content-header-btn-group').on('click', 'a', function () {

        switch ($(this).attr('data-a-target')) {

            case "lang" : {
               LSorage.write('lang', $(this).attr('data-lang'));
            }
        }


    });

}();