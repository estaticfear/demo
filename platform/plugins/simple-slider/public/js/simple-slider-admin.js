(() => {
    function t(e) {
        return (
            (t =
                "function" == typeof Symbol &&
                "symbol" == typeof Symbol.iterator
                    ? function (t) {
                          return typeof t;
                      }
                    : function (t) {
                          return t &&
                              "function" == typeof Symbol &&
                              t.constructor === Symbol &&
                              t !== Symbol.prototype
                              ? "symbol"
                              : typeof t;
                      }),
            t(e)
        );
    }
    function e(e, o) {
        for (var r = 0; r < o.length; r++) {
            var n = o[r];
            (n.enumerable = n.enumerable || !1),
                (n.configurable = !0),
                "value" in n && (n.writable = !0),
                Object.defineProperty(
                    e,
                    ((a = n.key),
                    (i = void 0),
                    (i = (function (e, o) {
                        if ("object" !== t(e) || null === e) return e;
                        var r = e[Symbol.toPrimitive];
                        if (void 0 !== r) {
                            var n = r.call(e, o || "default");
                            if ("object" !== t(n)) return n;
                            throw new TypeError(
                                "@@toPrimitive must return a primitive value."
                            );
                        }
                        return ("string" === o ? String : Number)(e);
                    })(a, "string")),
                    "symbol" === t(i) ? i : String(i)),
                    n
                );
        }
        var a, i;
    }
    var o = (function () {
        function t() {
            !(function (t, e) {
                if (!(t instanceof e))
                    throw new TypeError("Cannot call a class as a function");
            })(this, t);
        }
        var o, r, n;
        return (
            (o = t),
            (r = [
                {
                    key: "init",
                    value: function () {
                        $.each(
                            $("#simple-slider-items-table_wrapper tbody"),
                            function (t, e) {
                                Sortable.create(e, {
                                    group: e + "_" + t,
                                    sort: !0,
                                    delay: 0,
                                    disabled: !1,
                                    store: null,
                                    animation: 150,
                                    handle: "tr",
                                    ghostClass: "sortable-ghost",
                                    chosenClass: "sortable-chosen",
                                    dataIdAttr: "data-id",
                                    forceFallback: !1,
                                    fallbackClass: "sortable-fallback",
                                    fallbackOnBody: !1,
                                    scroll: !0,
                                    scrollSensitivity: 30,
                                    scrollSpeed: 10,
                                    onEnd: function () {
                                        var t = $(e).closest(".widget-body");
                                        t
                                            .find(".btn-save-sort-order")
                                            .addClass("sort-button-active")
                                            .show(),
                                            $.each(
                                                t.find("tbody tr"),
                                                function (t, e) {
                                                    $(e)
                                                        .find(".order-column")
                                                        .text(t + 1);
                                                }
                                            );
                                    },
                                });
                            }
                        ),
                            $(".btn-save-sort-order")
                                .off("click")
                                .on("click", function (t) {
                                    t.preventDefault();
                                    var e = $(t.currentTarget);
                                    if (e.hasClass("sort-button-active")) {
                                        var o = e.closest(".widget-body");
                                        o.find(".btn-save-sort-order").addClass(
                                            "button-loading"
                                        );
                                        var r = [];
                                        console.log(o.find("tbody tr")),
                                            $.each(
                                                o.find("tbody tr"),
                                                function (t, e) {
                                                    r.push(
                                                        parseInt(
                                                            $(e)
                                                                .find(
                                                                    "td:first-child"
                                                                )
                                                                .text()
                                                        )
                                                    ),
                                                        $(e)
                                                            .find(
                                                                ".order-column"
                                                            )
                                                            .text(t + 1);
                                                }
                                            ),
                                            $.ajax({
                                                type: "POST",
                                                cache: !1,
                                                url: route(
                                                    "simple-slider.sorting"
                                                ),
                                                data: { items: r },
                                                success: function (t) {
                                                    Cmat.showSuccess(t.message),
                                                        o
                                                            .find(
                                                                ".btn-save-sort-order"
                                                            )
                                                            .removeClass(
                                                                "button-loading"
                                                            )
                                                            .hide(),
                                                        e.removeClass(
                                                            "sort-button-active"
                                                        );
                                                },
                                                error: function (t) {
                                                    Cmat.showError(t.message),
                                                        e.removeClass(
                                                            "sort-button-active"
                                                        );
                                                },
                                            });
                                    }
                                });
                    },
                },
            ]) && e(o.prototype, r),
            n && e(o, n),
            Object.defineProperty(o, "prototype", { writable: !1 }),
            t
        );
    })();
    $(document).ready(function () {
        new o().init();
    });
})();