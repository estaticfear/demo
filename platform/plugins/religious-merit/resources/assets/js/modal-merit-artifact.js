$(function () {
    let artifacts = [];

    const handleTableFormArtifact = function (data) {
        let tbody = '<tbody class="table-light">';
        if (data.length) {
            for (i = 0; i < data.length; i++) {
                tbody += `<tr class="align-middle">
                        <td class="text-center">${i + 1}</td>
                        <td><img src="${data[i].image}" alt="${
                    data[i].name
                }" loading="lazy" class="project-artifact-image"></td>
                        <td>${data[i].name}</td>
                        <td class="text-end">${data[i].quantity}</td>
                        <td class="text-center">
                            <img src="/vendor/core/plugins/religious-merit/images/trash.png" alt="trash" class="btn-remove-artifact" data-artifact-id="${
                                data[i].id
                            }" role="button">
                        </td>
                    </tr>`;
            }
        } else {
            tbody += `<tr>
                    <td class="text-center" colspan="5">
                        <img src="/vendor/core/plugins/religious-merit/images/lotus.png" alt="lotus" class="mt-3 mb-4">
                        <p class="text-muted">Hiện chưa có hiện vật Công Đức.<br />Vui lòng "Thêm hiện vật"</p>
                    </td>
                </tr>`;
        }
        tbody += '</tbody>';
        $('.table-form-artifact tbody').replaceWith(tbody);
    };

    $('.merit-artifact-action').on('click', function () {
        const _self = $(this);
        const projectId = _self.data('project');

        const modal = $('.modal-merit-artifact');

        // set project id to hidden field
        const projectField = modal.find('#project-id');
        projectField.val(projectId);

        // show modal
        modal.modal('show');
        $('.merit-artifact-form').show();
        $('.merit-artifact-list').hide();
        artifacts = [];
        handleTableFormArtifact(artifacts);
        $('.merit-artifact-form-message').html('').removeClass('alert alert-danger mt-3');

        const form = $('.merit-artifact-form');
        form.find('input').removeClass('is-invalid');
        form.find('.feedback').removeClass('invalid-feedback');
        form.find('.feedback').html('');
    });

    $('.btn-add-artifact').on('click', function () {
        $('.merit-artifact-form').hide();
        $('.merit-artifact-list').show();
    });

    $(document).on('click', '.btn-remove-artifact', function () {
        const id = $(this).data('artifact-id');
        artifacts = artifacts.filter((artifact) => artifact.id != id);
        handleTableFormArtifact(artifacts);
    });

    const searchInput = $('.input-search-merit-artifacts');
    const rows = $('.artifact-row');
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

    $('.btn-submit-merit-artifact-list').on('click', function () {
        const artifactRows = document.querySelectorAll('.artifact-row');
        artifacts = [];

        artifactRows.forEach((element) => {
            const quantityOrigin = parseInt($(element).find('input[name="quantityOrigin"]').val());
            const quantity = parseInt($(element).find('input[name="quantity"]').val());
            const selected = $(element).find('input[name="checkbox"]:checked').val();
            if (quantity > 0 && selected) {
                const obj = {
                    id: $(element).find('input[name="id"]').val(),
                    image: $(element).find('input[name="image"]').val(),
                    name: $(element).find('input[name="name"]').val(),
                    quantity: quantity > quantityOrigin ? quantityOrigin : quantity,
                };
                artifacts.push(obj);
            }
        });
        handleTableFormArtifact(artifacts);
        $('.merit-artifact-form').show();
        $('.merit-artifact-list').hide();
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

    $(document).on('click', '.btn-submit-merit-artifact-form', function (event) {
        event.preventDefault();
        event.stopPropagation();

        const form = $('.merit-artifact-form');
        const data = new FormData(form[0]);
        data.set('is_hidden', data.get('is_hidden') === 'on' ? 1 : 0);
        if (artifacts.length) {
            artifacts.map((artifact) => data.set(`merit_products[${artifact.id}]`, artifact.quantity));
        } else {
            data.set('merit_products', null);
        }
        $('.merit-artifact-form-message').html('').removeClass('alert alert-danger mt-3');

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
                    $('.modal-merit-artifact').modal('hide');
                    $('.modal-thank-message-merit-artifact').modal('show');
                } else {
                    if (res.message) {
                        $('.merit-artifact-form-message').html(res.message).addClass('alert alert-danger mt-3');
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
