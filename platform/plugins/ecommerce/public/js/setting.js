$(document).ready(function () {
    $(document).on("keyup", "#store_order_prefix", function (e) {
        $(e.currentTarget).val()
            ? $(".sample-order-code-prefix").text(
                  $(e.currentTarget).val() + "-"
              )
            : $(".sample-order-code-prefix").text("");
    }),
        $(document).on("keyup", "#store_order_suffix", function (e) {
            $(e.currentTarget).val()
                ? $(".sample-order-code-suffix").text(
                      "-" + $(e.currentTarget).val()
                  )
                : $(".sample-order-code-suffix").text("");
        }),
        $(document).on("change", ".check-all", function (e) {
            var t = $(e.currentTarget),
                r = t.attr("data-set"),
                n = t.prop("checked");
            $(r).each(function (e, t) {
                n ? $(t).prop("checked", !0) : $(t).prop("checked", !1);
            });
        }),
        $(".trigger-input-option").on("change", function () {
            var e = $($(this).data("setting-container"));
            "1" == $(this).val()
                ? (e.removeClass("d-none"), Cmat.initResources())
                : e.addClass("d-none");
        });
});
