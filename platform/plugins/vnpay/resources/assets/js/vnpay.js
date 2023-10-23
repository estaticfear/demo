$(document).on('click', '.re-sync-vnpay', event => {
    let _self = $(event.currentTarget);

    const icon = _self.find('.fa');
    icon.addClass('fa-spin');

    const resyncURL = _self.data('section');

    $.ajax({
        url: resyncURL,
        type: 'POST',
        success: data => {
            if (data.error) {
                Cmat.showError(data.message);
            } else {
                const tableId = _self.parents().find('table').attr('id');
                Cmat.showSuccess(data.message);
                window.x = window.LaravelDataTables[tableId].draw();
            }

            icon.removeClass('fa-spin');
        },
        error: data => {
            Cmat.handleError(data);
            icon.removeClass('fa-spin');
        }
    });
})

