$(function () {
    const addCommas = function (nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        const rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    };

    // modal project report
    const meritReportModal = $('.modal-merit-report');
    const meritReportBtn = $('.merit-report-btn');
    meritReportBtn.on('click', function () {
        if (meritReportModal.length) {
            const _self = $(this);
            const projectId = _self.data('project');
            const form = $('#merit-report-form');
            const data = new FormData(form[0]);
            const budgetsRow = $('.report-table-budgets');
            const budgetsTable = $('#report-table-budgets');
            const meritsRow = $('.report-table-merits');
            const meritsTable = $('#report-table-merits');
            meritReportModal.modal('show');
            // Call api lấy thông tin
            $.ajax({
                type: 'POST',
                cache: false,
                url: `/project/merits/report/${projectId}`,
                data,
                contentType: false,
                processData: false,
                success: (res) => {
                    if (!res.error) {
                        budgetsTable.html(res.budgets);
                        meritsTable.html(res.merits);
                    } else {
                        console.log('error', res.message);
                    }
                },
                error: (res) => {
                    handleError(res, form);
                },
            });
            // budgets ajax
            if (budgetsRow.length) {
                const searchBudgetInput = budgetsRow.find('.input-search-budgets');
                const getBudgets = function (page, search = '') {
                    $.ajax({
                        type: 'GET',
                        url: `/project/budgets/${projectId}?page=${page}&search=${search}`,
                        success: (data) => {
                            budgetsTable.html(data);
                        },
                    });
                };
                searchBudgetInput.on('keypress', function (e) {
                    if (e.key === 'Enter') {
                        getBudgets(1, searchBudgetInput.val());
                    }
                });
                budgetsRow.on('click', '.budgets-ajax .pagination a', function (e) {
                    e.preventDefault();
                    const page = $(this).attr('href').split('page=')[1];
                    getBudgets(page, searchBudgetInput.val());
                });
            }
            // merits ajax
            if (meritsRow.length) {
                const searchInput = meritsRow.find('.input-search-merits');
                const getMerits = function (page, search = '') {
                    $.ajax({
                        type: 'GET',
                        url: `/project/merits/${projectId}?page=${page}&search=${search}`,
                        success: (data) => {
                            meritsTable.html(data);
                        },
                    });
                };
                searchInput.on('keypress', function (e) {
                    if (e.key === 'Enter') {
                        getMerits(1, searchInput.val());
                    }
                });
                meritsRow.on('click', '.ajax .pagination a', function (e) {
                    e.preventDefault();
                    const page = $(this).attr('href').split('page=')[1];
                    getMerits(page, searchInput.val());
                });
            }
        }
    });
    // End of modal project report

    $('.merit-action').on('click', function () {
        const _self = $(this);
        const projectId = _self.data('project');

        const modal = $('.modal-merit');

        // set project id to hidden field
        const projectField = modal.find('#project-id');
        projectField.val(projectId);

        // show modal
        modal.modal('show');
        $('.merit-form-input').show();
        $('.transfer-info').hide();
        $('.form-upload-image').hide();
        $('.btn-submit-merit').show();
        $('.upload-image-message').html('');
        $('.upload-image-message').removeClass('alert alert-danger my-3');
        $('.input-upload-image').val('');
        $('.btn-complete-merit').hide();

        const form = $('.merit-form');
        form.find('input').removeClass('is-invalid');
        form.find('.feedback').removeClass('invalid-feedback');
        form.find('.feedback').html('');
        form.find('input#amount').val(addCommas(50000));
    });

    $('.merit-tab').on('click', function () {
        $('.merit-tab').removeClass('active');
        $(this).addClass('active');
        $('.merit-tab-content').hide();

        const activeTab = $(this).find('a').attr('href');
        $(activeTab).show();

        $('#payment_gate').val(activeTab === '#vnpay' ? 'vnpay' : 'transfer');

        $('.merit-form-input').show();
        $('.transfer-info').hide();
        $('.form-upload-image').hide();
        $('.btn-submit-merit').show();
        $('.upload-image-message').html('');
        $('.upload-image-message').removeClass('alert alert-danger my-3');
        $('.input-upload-image').val('');
        $('.btn-complete-merit').hide();
        return false;
    });

    $('.btn-complete-merit').on('click', function () {
        // Toastify({
        //     // duration: 9999999,
        //     text: 'Cảm ơn quý Phật tử đã đóng góp',
        //     className: 'text-white bg-success bg-gradient',
        //     style: {
        //         'font-weight': '500',
        //     },
        // }).showToast();
        $('.modal-merit').modal('hide');
        $('.modal-thank-message-merit').modal('show');
    });

    $('input#amount').on('keyup', function (e) {
        $('input#amount').val(addCommas(e.target.value.replaceAll(',', '')));
    });
});

$(function () {
    const showError = function (message) {
        $('.contact-error-message').html(message).show();
    };

    const showSuccess = function (message) {
        $('.contact-success-message').html(message).show();
    };

    const handleError = function (data, form) {
        if (typeof data.errors !== 'undefined' && data.errors.length) {
            handleValidationError(data.errors);
        } else {
            if (typeof data.responseJSON !== 'undefined') {
                if (typeof data.responseJSON.errors !== 'undefined') {
                    if (data.status === 422) {
                        handleValidationError(data.responseJSON.errors, form);
                    }
                } else if (typeof data.responseJSON.message !== 'undefined') {
                    showError(data.responseJSON.message);
                } else {
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            showError(item);
                        });
                    });
                }
            } else {
                showError(data.statusText);
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

    $(document).on('click', '.btn-submit-merit', function (event) {
        event.preventDefault();
        event.stopPropagation();

        $(this).addClass('button-loading');
        $('.contact-success-message').html('').hide();
        $('.contact-error-message').html('').hide();

        const form = $('#merit-form');
        const data = new FormData(form[0]);
        data.set('amount', data.get('amount').replaceAll(',', ''));
        data.set('is_hidden', data.get('is_hidden') === 'on' ? 1 : 0);

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

                    if (res.data.payment_url) {
                        window.location.href = res.data.payment_url;
                        return;
                    } else {
                        $('.qrcode').attr(
                            'src',
                            $('.qrcode-template').val() +
                                `&amount=${res.data.amount}&addInfo=${res.data.transaction_message}`,
                        );
                        $('.transfer-content').val(`${res.data.transaction_message}`);
                        $('.merit-form-input').hide();
                        $('.transfer-info').show();
                        $('.form-upload-image').show();
                        $('.btn-complete-merit').show();
                        $('.btn-submit-merit').hide();
                    }

                    // reset fields
                    form.find('#name').val('');
                    form.find('#address').val('');
                    form.find('input[type=tel]').val('');
                    form.find('input[type=number]').val('');
                    form.find('input[type=email]').val('');
                    form.find('input[type="checkbox"]').prop('checked', false);
                    form.find('textarea').val('');

                    showSuccess(res.message);
                } else {
                    showError(res.message);
                }

                $(this).removeClass('button-loading');

                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }
            },
            error: (res) => {
                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }
                $(this).removeClass('button-loading');
                handleError(res, form);
            },
        });
    });

    const maximumSize = 2; //MB
    $(document).on('click', '.btn-upload-image', function (event) {
        event.preventDefault();
        event.stopPropagation();

        const form = $(this).closest('form');
        const data = new FormData(form[0]);
        const file = data.get('file');
        if (file.size > maximumSize * 1024 * 1024) {
            $('.upload-image-message')
                .html(`File tải lên phải nhỏ hơn ${maximumSize}MB`)
                .addClass('alert alert-success my-3');
            return;
        }

        $.ajax({
            type: 'POST',
            cache: false,
            url: form.attr('action'),
            data,
            contentType: false,
            processData: false,
            success: (res) => {
                if (!res.error) {
                    if (data.get('merit_id')) {
                        // Nếu là trang danh sách dự án chưa hoàn thành của cá nhân, thì reload
                        window.location.reload();
                        return;
                    }
                    $('.upload-image-message').html('Tải ảnh lên thành công').addClass('alert');
                    $('.form-upload-image').hide();
                    $('.btn-complete-merit').show();
                } else {
                    $('.upload-image-message').html('Tải ảnh lên không thành công').addClass('alert alert-danger my-3');
                }
            },
            error: () => {
                $('.upload-image-message').html('Tải ảnh lên không thành công').addClass('alert alert-danger my-3');
            },
        });
    });
});
