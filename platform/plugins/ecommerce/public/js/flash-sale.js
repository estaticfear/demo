$(document).ready(function () {
    $(document).on("click", ".list-search-data .selectable-item", function (e) {
        e.preventDefault();
        var t = $(e.currentTarget),
            a = t.closest(".form-group").find("input[type=hidden]"),
            r = [];
        if (
            ($.each(a.val().split(","), function (e, t) {
                t && "" !== t && (r[e] = parseInt(t));
            }),
            $.inArray(t.data("id"), r) < 0)
        ) {
            a.val() ? a.val(a.val() + "," + t.data("id")) : a.val(t.data("id"));
            var n = $(document)
                .find("#selected_product_list_template")
                .html()
                .replace(/__name__/gi, t.data("name"))
                .replace(/__id__/gi, t.data("id"))
                .replace(/__index__/gi, r.length)
                .replace(/__url__/gi, t.data("url"))
                .replace(/__image__/gi, t.data("image"))
                .replace(/__price__/gi, t.data("price"))
                .replace(/__attributes__/gi, t.find("a span").text());
            t
                .closest(".form-group")
                .find(".list-selected-products")
                .removeClass("hidden"),
                t
                    .closest(".form-group")
                    .find(".list-selected-products table tbody")
                    .append(n);
        }
        t.closest(".panel").addClass("hidden");
    }),
        $(document).on("click", ".textbox-advancesearch", function (e) {
            var t = $(e.currentTarget),
                a = t.closest(".box-search-advance").find(".panel");
            a.removeClass("hidden"),
                a.addClass("active"),
                0 === a.find(".panel-body").length &&
                    (Cmat.blockUI({
                        target: a,
                        iconOnly: !0,
                        overlayColor: "none",
                    }),
                    $.ajax({
                        url: t.data("target"),
                        type: "GET",
                        success: function (e) {
                            e.error
                                ? Cmat.showError(e.message)
                                : (a.html(e.data), Cmat.unblockUI(a));
                        },
                        error: function (e) {
                            Cmat.handleError(e), Cmat.unblockUI(a);
                        },
                    }));
        }),
        $(document).on("keyup", ".textbox-advancesearch", function (e) {
            var t = $(e.currentTarget),
                a = t.closest(".box-search-advance").find(".panel");
            setTimeout(function () {
                Cmat.blockUI({ target: a, iconOnly: !0, overlayColor: "none" }),
                    $.ajax({
                        url: t.data("target") + "?keyword=" + t.val(),
                        type: "GET",
                        success: function (e) {
                            e.error
                                ? Cmat.showError(e.message)
                                : (a.html(e.data), Cmat.unblockUI(a));
                        },
                        error: function (e) {
                            Cmat.handleError(e), Cmat.unblockUI(a);
                        },
                    });
            }, 500);
        }),
        $(document).on("click", ".box-search-advance .page-link", function (e) {
            e.preventDefault();
            var t = $(e.currentTarget)
                .closest(".box-search-advance")
                .find(".textbox-advancesearch");
            if (
                !t.closest(".page-item").hasClass("disabled") &&
                t.data("target")
            ) {
                var a = t.closest(".box-search-advance").find(".panel");
                Cmat.blockUI({ target: a, iconOnly: !0, overlayColor: "none" }),
                    $.ajax({
                        url:
                            $(e.currentTarget).prop("href") +
                            "&keyword=" +
                            t.val(),
                        type: "GET",
                        success: function (e) {
                            e.error
                                ? Cmat.showError(e.message)
                                : (a.html(e.data), Cmat.unblockUI(a));
                        },
                        error: function (e) {
                            Cmat.handleError(e), Cmat.unblockUI(a);
                        },
                    });
            }
        }),
        $(document).on("click", "body", function (e) {
            var t = $(".box-search-advance");
            t.is(e.target) ||
                0 !== t.has(e.target).length ||
                t.find(".panel").addClass("hidden");
        }),
        $(document).on(
            "click",
            ".btn-trigger-remove-selected-product",
            function (e) {
                e.preventDefault();
                var t = $(e.currentTarget)
                        .closest(".form-group")
                        .find("input[type=hidden]"),
                    a = t.val().split(",");
                $.each(a, function (e, t) {
                    (t = t.trim()), _.isEmpty(t) || (a[e] = parseInt(t));
                });
                var r = a.indexOf($(e.currentTarget).data("id"));
                r > -1 && delete a[r],
                    t.val(a.join(",")),
                    $(e.currentTarget).closest("tbody").find("tr").length < 2 &&
                        $(e.currentTarget)
                            .closest(".list-selected-products")
                            .addClass("hidden"),
                    $(e.currentTarget)
                        .closest("tbody")
                        .find(
                            "tr[data-product-id=" +
                                $(e.currentTarget).data("id") +
                                "]"
                        )
                        .remove();
            }
        );
});
