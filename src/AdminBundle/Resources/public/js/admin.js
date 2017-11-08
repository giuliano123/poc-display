$.fn.confirmDel = function () {
    return this.each(function () {
        var $el = $(this),
            $msg = $el.data('msg');

        $msg = 'Attention vous êtes sur le point de supprimer ' + $msg + '. Veuillez confirmer cette action.';

        $el.on('click', function (e) {
            if (!confirm($msg)) {
                e.preventDefault();
            }
            ;
        });
    });
};

$(function () {
    $('[data-function="confirmDel"]').confirmDel();
    $('[data-date="date"]')
        .datepicker({
            'language': 'fr',
            'format': 'dd/mm/yyyy',
            pickTime: false,
            'useCurrent': false
        });
    $('[data-date="datetime"]')
        .datepicker({
            'language': 'fr',
            'format': 'dd/mm/yyyy',
            'useCurrent': false
        });
});
