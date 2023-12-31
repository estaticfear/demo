$(document).ready(function () {
    $(document).on("click", "#is_change_password", function (e) {
        $(e.currentTarget).is(":checked")
            ? $("input[type=password]")
                  .closest(".form-group")
                  .removeClass("hidden")
                  .fadeIn()
            : $("input[type=password]")
                  .closest(".form-group")
                  .addClass("hidden")
                  .fadeOut();
    }),
        $(document).on("click", ".verify-customer-email-button", function (e) {
            e.preventDefault(),
                $("#confirm-verify-customer-email-button").data(
                    "action",
                    $(e.currentTarget).prop("href")
                ),
                $("#verify-customer-email-modal").modal("show");
        }),
        $(document).on(
            "click",
            "#confirm-verify-customer-email-button",
            function (e) {
                e.preventDefault();
                var o = $(e.currentTarget);
                o.addClass("button-loading"),
                    $.ajax({
                        type: "POST",
                        cache: !1,
                        url: o.data("action"),
                        success: function (e) {
                            e.error
                                ? Cmat.showError(e.message)
                                : (Cmat.showSuccess(e.message),
                                  setTimeout(function () {
                                      window.location.reload();
                                  }, 2e3)),
                                o.removeClass("button-loading"),
                                o.closest(".modal").modal("hide");
                        },
                        error: function (e) {
                            Cmat.handleError(e),
                                o.removeClass("button-loading");
                        },
                    });
            }
        );
});
