(() => {
    function e(o) {
        return (
            (e =
                "function" == typeof Symbol &&
                "symbol" == typeof Symbol.iterator
                    ? function (e) {
                          return typeof e;
                      }
                    : function (e) {
                          return e &&
                              "function" == typeof Symbol &&
                              e.constructor === Symbol &&
                              e !== Symbol.prototype
                              ? "symbol"
                              : typeof e;
                      }),
            e(o)
        );
    }
    function o(o, r) {
        for (var t = 0; t < r.length; t++) {
            var n = r[t];
            (n.enumerable = n.enumerable || !1),
                (n.configurable = !0),
                "value" in n && (n.writable = !0),
                Object.defineProperty(
                    o,
                    ((a = n.key),
                    (i = void 0),
                    (i = (function (o, r) {
                        if ("object" !== e(o) || null === o) return o;
                        var t = o[Symbol.toPrimitive];
                        if (void 0 !== t) {
                            var n = t.call(o, r || "default");
                            if ("object" !== e(n)) return n;
                            throw new TypeError(
                                "@@toPrimitive must return a primitive value."
                            );
                        }
                        return ("string" === r ? String : Number)(o);
                    })(a, "string")),
                    "symbol" === e(i) ? i : String(i)),
                    n
                );
        }
        var a, i;
    }
    var r = (function () {
        function e() {
            !(function (e, o) {
                if (!(e instanceof o))
                    throw new TypeError("Cannot call a class as a function");
            })(this, e);
        }
        var r, t, n;
        return (
            (r = e),
            (t = [
                {
                    key: "init",
                    value: function () {
                        $(document).on(
                            "click",
                            ".btn-update-order",
                            function (e) {
                                e.preventDefault();
                                var o = $(e.currentTarget);
                                o.addClass("button-loading"),
                                    $.ajax({
                                        type: "POST",
                                        cache: !1,
                                        url: o.closest("form").prop("action"),
                                        data: o.closest("form").serialize(),
                                        success: function (e) {
                                            e.error
                                                ? Cmat.showError(e.message)
                                                : Cmat.showSuccess(e.message),
                                                o.removeClass("button-loading");
                                        },
                                        error: function (e) {
                                            Cmat.handleError(e),
                                                o.removeClass("button-loading");
                                        },
                                    });
                            }
                        ),
                            $(document).on(
                                "click",
                                ".btn-trigger-send-order-recover-modal",
                                function (e) {
                                    e.preventDefault(),
                                        $(
                                            "#confirm-send-recover-email-button"
                                        ).data(
                                            "action",
                                            $(e.currentTarget).data("action")
                                        ),
                                        $(
                                            "#send-order-recover-email-modal"
                                        ).modal("show");
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-send-recover-email-button",
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
                                                    : Cmat.showSuccess(
                                                          e.message
                                                      ),
                                                    o.removeClass(
                                                        "button-loading"
                                                    ),
                                                    $(
                                                        "#send-order-recover-email-modal"
                                                    ).modal("hide");
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e),
                                                    o.removeClass(
                                                        "button-loading"
                                                    );
                                            },
                                        });
                                }
                            );
                    },
                },
            ]) && o(r.prototype, t),
            n && o(r, n),
            Object.defineProperty(r, "prototype", { writable: !1 }),
            e
        );
    })();
    $(document).ready(function () {
        new r().init();
    });
})();
