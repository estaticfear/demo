$(function () {
    let efforts = [];

    const handleTableFormEffort = function (data) {
        let tbody = '<tbody class="table-light">';
        if (data.length) {
            for (i = 0; i < data.length; i++) {
                tbody += `<tr>
                        <td class="text-center">${i + 1}</td>
                        <td>${data[i].name}</td>
                        <td class="text-end">${data[i].quantity}</td>
                        <td class="text-center">
                            <img src="/vendor/core/plugins/religious-merit/images/trash.png" alt="trash" class="btn-remove-effort" data-effort-id="${
                                data[i].id
                            }" role="button">
                        </td>
                    </tr>`;
            }
        } else {
            tbody += `<tr>
                    <td class="text-center" colspan="4">
                        <img src="/vendor/core/plugins/religious-merit/images/lotus.png" alt="lotus" class="mt-3 mb-4">
                        <p class="text-muted">Hiện chưa có ngày công Công Đức.<br />Vui lòng "Thêm ngày công"</p>
                    </td>
                </tr>`;
        }
        tbody += '</tbody>';
        $('.table-form-effort tbody').replaceWith(tbody);
    };

    $('.merit-effort-action').on('click', function () {
        const _self = $(this);
        const projectId = _self.data('project');

        const modal = $('.modal-merit-effort');

        // set project id to hidden field
        const projectField = modal.find('#project-id');
        projectField.val(projectId);

        // show modal
        modal.modal('show');
        $('.merit-effort-form').show();
        $('.merit-effort-list').hide();
        efforts = [];
        handleTableFormEffort(efforts);
        $('.merit-effort-form-message').html('').removeClass('alert alert-danger mt-3');

        const form = $('.merit-effort-form');
        form.find('input').removeClass('is-invalid');
        form.find('.feedback').removeClass('invalid-feedback');
        form.find('.feedback').html('');
    });

    $('.btn-add-effort').on('click', function () {
        $('.merit-effort-form').hide();
        $('.merit-effort-list').show();
    });

    $(document).on('click', '.btn-remove-effort', function () {
        const id = $(this).data('effort-id');
        efforts = efforts.filter((effort) => effort.id != id);
        handleTableFormEffort(efforts);
    });

    const searchInput = $('.input-search-merit-efforts');
    const rows = $('.effort-row');
    searchInput.on('keypress', function (e) {
        if (e.key === 'Enter') {
            if (searchInput.val()) {
                const show = rows
                    .filter(function () {
                        return $(this).data('product-name').match(searchInput.val().replace(/\s/g, ''));
                    })
                    .show();
                rows.not(show).hide();
            } else {
                rows.show();
            }
        }
    });

    $('.btn-submit-merit-effort-list').on('click', function () {
        const effortRows = document.querySelectorAll('.effort-row');
        efforts = [];

        effortRows.forEach((element) => {
            const quantityOrigin = parseInt($(element).find('input[name="quantityOrigin"]').val());
            const quantity = parseInt($(element).find('input[name="quantity"]').val());
            const selected = $(element).find('input[name="checkbox"]:checked').val();

            if (quantity > 0 && selected) {
                const obj = {
                    id: $(element).find('input[name="id"]').val(),
                    name: $(element).find('input[name="name"]').val(),
                    quantity: quantity > quantityOrigin ? quantityOrigin : quantity,
                };
                efforts.push(obj);
            }
        });

        handleTableFormEffort(efforts);
        $('.merit-effort-form').show();
        $('.merit-effort-list').hide();
    });

    const handleError = function (data, form) {
        if (typeof data.errors !== 'undefined' && data.errors.length) {
            handleValidationError(data.errors);
        } else {
            if (typeof data.responseJSON !== 'undefined') {
                if (typeof data.responseJSON.errors !== 'undefined') {
                    if (data.status === 422) {
                        handleValidationError(data.responseJSON.errors, form);
                    }
                }
            }
        }
    };

    const handleValidationError = function (errors, form) {
        form.find('input').removeClass('is-invalid');
        form.find('.feedback').removeClass('invalid-feedback');
        form.find('.feedback').html('');

        for (const fieldName in errors) {
            const field = form.find(`input[name="${fieldName}"]`);
            if (field.length === 1) {
                const message = errors[fieldName][0];
                field.addClass('is-invalid');

                const feedbackDiv = field.find('~.feedback');
                feedbackDiv.addClass('invalid-feedback');
                feedbackDiv.html(message);
            }

            if (fieldName === 'g-recaptcha-response') {
                const message = errors[fieldName][0];
                $('.feedback-captcha').addClass('invalid-feedback d-block').html(message);
            }
        }
    };

    $(document).on('click', '.btn-submit-merit-effort-form', function (event) {
        event.preventDefault();
        event.stopPropagation();

        const form = $('.merit-effort-form');
        const data = new FormData(form[0]);
        data.set('is_hidden', data.get('is_hidden') === 'on' ? 1 : 0);
        if (efforts.length) {
            efforts.map((effort) => data.set(`merit_products[${effort.id}]`, effort.quantity));
        } else {
            data.set('merit_products', null);
        }
        $('.merit-effort-form-message').html('').removeClass('alert alert-danger mt-3');

        $.ajax({
            type: 'POST',
            cache: false,
            url: form.prop('action'),
            data,
            contentType: false,
            processData: false,
            success: (res) => {
                if (!res.error) {
                    // reset validation
                    form.find('input').removeClass('is-invalid');
                    form.find('.feedback').removeClass('invalid-feedback');
                    form.find('.feedback').html('');

                    // reset fields
                    form.find('input[name="name"]').val('');
                    form.find('input[name="phone"]').val('');
                    form.find('input[name="address"]').val('');
                    form.find('input[name="email"]').val('');
                    form.find('input[name="is_hidden"]').prop('checked', false);

                    // thank message
                    $('.modal-merit-effort').modal('hide');
                    $('.modal-thank-message-merit-effort').modal('show');
                } else {
                    if (res.message) {
                        $('.merit-effort-form-message').html(res.message).addClass('alert alert-danger mt-3');
                    }
                }

                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }
            },
            error: (res) => {
                handleError(res, form);

                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }
            },
        });
    });
});
