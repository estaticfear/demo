(() => {
    "use strict";
    function e(t) {
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
            e(t)
        );
    }
    function t(t, n) {
        for (var o = 0; o < n.length; o++) {
            var a = n[o];
            (a.enumerable = a.enumerable || !1),
                (a.configurable = !0),
                "value" in a && (a.writable = !0),
                Object.defineProperty(
                    t,
                    ((r = a.key),
                    (i = void 0),
                    (i = (function (t, n) {
                        if ("object" !== e(t) || null === t) return t;
                        var o = t[Symbol.toPrimitive];
                        if (void 0 !== o) {
                            var a = o.call(t, n || "default");
                            if ("object" !== e(a)) return a;
                            throw new TypeError(
                                "@@toPrimitive must return a primitive value."
                            );
                        }
                        return ("string" === n ? String : Number)(t);
                    })(r, "string")),
                    "symbol" === e(i) ? i : String(i)),
                    a
                );
        }
        var r, i;
    }
    var n = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function");
            })(this, e);
        }
        var n, o, a;
        return (
            (n = e),
            (o = [
                {
                    key: "init",
                    value: function () {
                        $(".toggle-payment-item")
                            .off("click")
                            .on("click", function (e) {
                                $(e.currentTarget)
                                    .closest("tbody")
                                    .find(".payment-content-item")
                                    .toggleClass("hidden");
                            }),
                            $(".disable-payment-item")
                                .off("click")
                                .on("click", function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    $(
                                        "#confirm-disable-payment-method-modal"
                                    ).modal("show"),
                                        $(
                                            "#confirm-disable-payment-method-button"
                                        ).on("click", function (e) {
                                            e.preventDefault(),
                                                $(e.currentTarget).addClass(
                                                    "button-loading"
                                                ),
                                                $.ajax({
                                                    type: "POST",
                                                    cache: !1,
                                                    url: $(
                                                        "div[data-disable-payment-url]"
                                                    ).data(
                                                        "disable-payment-url"
                                                    ),
                                                    data: {
                                                        type: t
                                                            .closest("form")
                                                            .find(
                                                                ".payment_type"
                                                            )
                                                            .val(),
                                                    },
                                                    success: function (e) {
                                                        e.error
                                                            ? Cmat.showError(
                                                                  e.message
                                                              )
                                                            : (t
                                                                  .closest(
                                                                      "tbody"
                                                                  )
                                                                  .find(
                                                                      ".payment-name-label-group"
                                                                  )
                                                                  .addClass(
                                                                      "hidden"
                                                                  ),
                                                              t
                                                                  .closest(
                                                                      "tbody"
                                                                  )
                                                                  .find(
                                                                      ".edit-payment-item-btn-trigger"
                                                                  )
                                                                  .addClass(
                                                                      "hidden"
                                                                  ),
                                                              t
                                                                  .closest(
                                                                      "tbody"
                                                                  )
                                                                  .find(
                                                                      ".save-payment-item-btn-trigger"
                                                                  )
                                                                  .removeClass(
                                                                      "hidden"
                                                                  ),
                                                              t
                                                                  .closest(
                                                                      "tbody"
                                                                  )
                                                                  .find(
                                                                      ".btn-text-trigger-update"
                                                                  )
                                                                  .addClass(
                                                                      "hidden"
                                                                  ),
                                                              t
                                                                  .closest(
                                                                      "tbody"
                                                                  )
                                                                  .find(
                                                                      ".btn-text-trigger-save"
                                                                  )
                                                                  .removeClass(
                                                                      "hidden"
                                                                  ),
                                                              t.addClass(
                                                                  "hidden"
                                                              ),
                                                              $(
                                                                  "#confirm-disable-payment-method-modal"
                                                              ).modal("hide"),
                                                              Cmat.showSuccess(
                                                                  e.message
                                                              )),
                                                            $(
                                                                "#confirm-disable-payment-method-button"
                                                            ).removeClass(
                                                                "button-loading"
                                                            );
                                                    },
                                                    error: function (e) {
                                                        Cmat.handleError(e),
                                                            $(
                                                                "#confirm-disable-payment-method-button"
                                                            ).removeClass(
                                                                "button-loading"
                                                            );
                                                    },
                                                });
                                        });
                                }),
                            $(".save-payment-item")
                                .off("click")
                                .on("click", function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: $(
                                                "div[data-update-payment-url]"
                                            ).data("update-payment-url"),
                                            data: t.closest("form").serialize(),
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : (t
                                                          .closest("tbody")
                                                          .find(
                                                              ".payment-name-label-group"
                                                          )
                                                          .removeClass(
                                                              "hidden"
                                                          ),
                                                      t
                                                          .closest("tbody")
                                                          .find(
                                                              ".method-name-label"
                                                          )
                                                          .text(
                                                              t
                                                                  .closest(
                                                                      "form"
                                                                  )
                                                                  .find(
                                                                      "input[name=name]"
                                                                  )
                                                                  .val()
                                                          ),
                                                      t
                                                          .closest("tbody")
                                                          .find(
                                                              ".disable-payment-item"
                                                          )
                                                          .removeClass(
                                                              "hidden"
                                                          ),
                                                      t
                                                          .closest("tbody")
                                                          .find(
                                                              ".edit-payment-item-btn-trigger"
                                                          )
                                                          .removeClass(
                                                              "hidden"
                                                          ),
                                                      t
                                                          .closest("tbody")
                                                          .find(
                                                              ".save-payment-item-btn-trigger"
                                                          )
                                                          .addClass("hidden"),
                                                      t
                                                          .closest("tbody")
                                                          .find(
                                                              ".btn-text-trigger-update"
                                                          )
                                                          .removeClass(
                                                              "hidden"
                                                          ),
                                                      t
                                                          .closest("tbody")
                                                          .find(
                                                              ".btn-text-trigger-save"
                                                          )
                                                          .addClass("hidden"),
                                                      Cmat.showSuccess(
                                                          e.message
                                                      )),
                                                    t.removeClass(
                                                        "button-loading"
                                                    );
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e),
                                                    t.removeClass(
                                                        "button-loading"
                                                    );
                                            },
                                        });
                                });
                    },
                },
            ]) && t(n.prototype, o),
            a && t(n, a),
            Object.defineProperty(n, "prototype", { writable: !1 }),
            e
        );
    })();
    $(document).ready(function () {
        new n().init();
    });
})();
