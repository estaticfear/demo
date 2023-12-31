(() => {
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
    function t(t, o) {
        for (var n = 0; n < o.length; n++) {
            var r = o[n];
            (r.enumerable = r.enumerable || !1),
                (r.configurable = !0),
                "value" in r && (r.writable = !0),
                Object.defineProperty(
                    t,
                    ((a = r.key),
                    (i = void 0),
                    (i = (function (t, o) {
                        if ("object" !== e(t) || null === t) return t;
                        var n = t[Symbol.toPrimitive];
                        if (void 0 !== n) {
                            var r = n.call(t, o || "default");
                            if ("object" !== e(r)) return r;
                            throw new TypeError(
                                "@@toPrimitive must return a primitive value."
                            );
                        }
                        return ("string" === o ? String : Number)(t);
                    })(a, "string")),
                    "symbol" === e(i) ? i : String(i)),
                    r
                );
        }
        var a, i;
    }
    var o = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function");
            })(this, e);
        }
        var o, n, r;
        return (
            (o = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        $(document).on(
                            "click",
                            ".btn-confirm-order",
                            function (e) {
                                e.preventDefault();
                                var t = $(e.currentTarget);
                                t.addClass("button-loading"),
                                    $.ajax({
                                        type: "POST",
                                        cache: !1,
                                        url: t.closest("form").prop("action"),
                                        data: t.closest("form").serialize(),
                                        success: function (e) {
                                            e.error
                                                ? Cmat.showError(e.message)
                                                : ($(
                                                      "#main-order-content"
                                                  ).load(
                                                      window.location.href +
                                                          " #main-order-content > *"
                                                  ),
                                                  t.closest("div").remove(),
                                                  Cmat.showSuccess(e.message)),
                                                t.removeClass("button-loading");
                                        },
                                        error: function (e) {
                                            Cmat.handleError(e),
                                                t.removeClass("button-loading");
                                        },
                                    });
                            }
                        ),
                            $(document).on(
                                "click",
                                ".btn-trigger-resend-order-confirmation-modal",
                                function (e) {
                                    e.preventDefault(),
                                        $(
                                            "#confirm-resend-confirmation-email-button"
                                        ).data(
                                            "action",
                                            $(e.currentTarget).data("action")
                                        ),
                                        $(
                                            "#resend-order-confirmation-email-modal"
                                        ).modal("show");
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-resend-confirmation-email-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t.data("action"),
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : Cmat.showSuccess(
                                                          e.message
                                                      ),
                                                    t.removeClass(
                                                        "button-loading"
                                                    ),
                                                    $(
                                                        "#resend-order-confirmation-email-modal"
                                                    ).modal("hide");
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e),
                                                    t.removeClass(
                                                        "button-loading"
                                                    );
                                            },
                                        });
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-shipment",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget),
                                        o = $(".shipment-create-wrap");
                                    o.toggleClass("hidden"),
                                        o.hasClass("shipment-data-loaded") ||
                                            (Cmat.blockUI({
                                                target: o,
                                                iconOnly: !0,
                                                overlayColor: "none",
                                            }),
                                            $.ajax({
                                                url: t.data("target"),
                                                type: "GET",
                                                success: function (e) {
                                                    e.error
                                                        ? Cmat.showError(
                                                              e.message
                                                          )
                                                        : (o.html(e.data),
                                                          o.addClass(
                                                              "shipment-data-loaded"
                                                          ),
                                                          Cmat.initResources()),
                                                        Cmat.unblockUI(o);
                                                },
                                                error: function (e) {
                                                    Cmat.handleError(e),
                                                        Cmat.unblockUI(o);
                                                },
                                            }));
                                }
                            ),
                            $(document).on("change", "#store_id", function (e) {
                                var t = $(".shipment-create-wrap");
                                Cmat.blockUI({
                                    target: t,
                                    iconOnly: !0,
                                    overlayColor: "none",
                                }),
                                    $("#select-shipping-provider").load(
                                        $(".btn-trigger-shipment").data(
                                            "target"
                                        ) +
                                            "?view=true&store_id=" +
                                            $(e.currentTarget).val() +
                                            " #select-shipping-provider > *",
                                        function () {
                                            Cmat.unblockUI(t),
                                                Cmat.initResources();
                                        }
                                    );
                            }),
                            $(document).on(
                                "change",
                                ".shipment-form-weight",
                                function (e) {
                                    var t = $(".shipment-create-wrap");
                                    Cmat.blockUI({
                                        target: t,
                                        iconOnly: !0,
                                        overlayColor: "none",
                                    }),
                                        $("#select-shipping-provider").load(
                                            $(".btn-trigger-shipment").data(
                                                "target"
                                            ) +
                                                "?view=true&store_id=" +
                                                $("#store_id").val() +
                                                "&weight=" +
                                                $(e.currentTarget).val() +
                                                " #select-shipping-provider > *",
                                            function () {
                                                Cmat.unblockUI(t),
                                                    Cmat.initResources();
                                            }
                                        );
                                }
                            ),
                            $(document).on(
                                "click",
                                ".table-shipping-select-options .clickable-row",
                                function (e) {
                                    var t = $(e.currentTarget);
                                    $(".input-hidden-shipping-method").val(
                                        t.data("key")
                                    ),
                                        $(".input-hidden-shipping-option").val(
                                            t.data("option")
                                        ),
                                        $(".input-show-shipping-method").val(
                                            t.find("span.ws-nm").text()
                                        );
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-create-shipment",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t
                                                .closest("form")
                                                .prop("action"),
                                            data: t.closest("form").serialize(),
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : (Cmat.showSuccess(
                                                          e.message
                                                      ),
                                                      $(
                                                          "#main-order-content"
                                                      ).load(
                                                          window.location.href +
                                                              " #main-order-content > *"
                                                      ),
                                                      $(
                                                          ".btn-trigger-shipment"
                                                      ).remove()),
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
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-cancel-shipment",
                                function (e) {
                                    e.preventDefault(),
                                        $(
                                            "#confirm-cancel-shipment-button"
                                        ).data(
                                            "action",
                                            $(e.currentTarget).data("action")
                                        ),
                                        $("#cancel-shipment-modal").modal(
                                            "show"
                                        );
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-cancel-shipment-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t.data("action"),
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : (Cmat.showSuccess(
                                                          e.message
                                                      ),
                                                      $(".carrier-status")
                                                          .addClass(
                                                              "carrier-status-" +
                                                                  e.data.status
                                                          )
                                                          .text(
                                                              e.data.status_text
                                                          ),
                                                      $(
                                                          "#cancel-shipment-modal"
                                                      ).modal("hide"),
                                                      $(
                                                          "#order-history-wrapper"
                                                      ).load(
                                                          window.location.href +
                                                              " #order-history-wrapper > *"
                                                      ),
                                                      $(
                                                          ".shipment-actions-wrapper"
                                                      ).remove()),
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
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-close-shipment-panel",
                                function (e) {
                                    e.preventDefault(),
                                        $(".shipment-create-wrap").addClass(
                                            "hidden"
                                        );
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-update-shipping-address",
                                function (e) {
                                    e.preventDefault(),
                                        $(
                                            "#update-shipping-address-modal"
                                        ).modal("show");
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-update-shipping-address-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t
                                                .closest(".modal-content")
                                                .find("form")
                                                .prop("action"),
                                            data: t
                                                .closest(".modal-content")
                                                .find("form")
                                                .serialize(),
                                            success: function (e) {
                                                if (e.error)
                                                    Cmat.showError(e.message);
                                                else {
                                                    Cmat.showSuccess(e.message),
                                                        $(
                                                            "#update-shipping-address-modal"
                                                        ).modal("hide"),
                                                        $(
                                                            ".shipment-address-box-1"
                                                        ).html(e.data.line),
                                                        $(
                                                            ".text-infor-subdued.shipping-address-info"
                                                        ).html(e.data.detail);
                                                    var o = $(
                                                        ".shipment-create-wrap"
                                                    );
                                                    Cmat.blockUI({
                                                        target: o,
                                                        iconOnly: !0,
                                                        overlayColor: "none",
                                                    }),
                                                        $(
                                                            "#select-shipping-provider"
                                                        ).load(
                                                            $(
                                                                ".btn-trigger-shipment"
                                                            ).data("target") +
                                                                "?view=true #select-shipping-provider > *",
                                                            function () {
                                                                Cmat.unblockUI(
                                                                    o
                                                                ),
                                                                    Cmat.initResources();
                                                            }
                                                        );
                                                }
                                                t.removeClass("button-loading");
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e),
                                                    t.removeClass(
                                                        "button-loading"
                                                    );
                                            },
                                        });
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-update-order",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t
                                                .closest("form")
                                                .prop("action"),
                                            data: t.closest("form").serialize(),
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : Cmat.showSuccess(
                                                          e.message
                                                      ),
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
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-cancel-order",
                                function (e) {
                                    e.preventDefault(),
                                        $("#confirm-cancel-order-button").data(
                                            "target",
                                            $(e.currentTarget).data("target")
                                        ),
                                        $("#cancel-order-modal").modal("show");
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-cancel-order-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t.data("target"),
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : (Cmat.showSuccess(
                                                          e.message
                                                      ),
                                                      $(
                                                          "#main-order-content"
                                                      ).load(
                                                          window.location.href +
                                                              " #main-order-content > *"
                                                      ),
                                                      $(
                                                          "#cancel-order-modal"
                                                      ).modal("hide")),
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
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-confirm-payment",
                                function (e) {
                                    e.preventDefault(),
                                        $("#confirm-payment-order-button").data(
                                            "target",
                                            $(e.currentTarget).data("target")
                                        ),
                                        $("#confirm-payment-modal").modal(
                                            "show"
                                        );
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-payment-order-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t.data("target"),
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : (Cmat.showSuccess(
                                                          e.message
                                                      ),
                                                      $(
                                                          "#main-order-content"
                                                      ).load(
                                                          window.location.href +
                                                              " #main-order-content > *"
                                                      ),
                                                      $(
                                                          "#confirm-payment-modal"
                                                      ).modal("hide")),
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
                                }
                            ),
                            $(document).on(
                                "click",
                                ".show-timeline-dropdown",
                                function (e) {
                                    e.preventDefault(),
                                        $(
                                            $(e.currentTarget).data("target")
                                        ).slideToggle(),
                                        $(e.currentTarget)
                                            .closest(".comment-log-item")
                                            .toggleClass("bg-white");
                                }
                            ),
                            $(document).on(
                                "keyup",
                                ".input-sync-item",
                                function (e) {
                                    var t = $(e.currentTarget).val();
                                    (t && !isNaN(t)) || (t = 0),
                                        $(e.currentTarget)
                                            .closest(".page-content")
                                            .find(
                                                $(e.currentTarget).data(
                                                    "target"
                                                )
                                            )
                                            .text(
                                                Cmat.numberFormat(
                                                    parseFloat(t),
                                                    2
                                                )
                                            );
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-refund",
                                function (e) {
                                    e.preventDefault(),
                                        $("#confirm-refund-modal").modal(
                                            "show"
                                        );
                                }
                            ),
                            $(document).on(
                                "change",
                                ".j-refund-quantity",
                                function () {
                                    var e = 0;
                                    $.each(
                                        $(".j-refund-quantity"),
                                        function (t, o) {
                                            var n = $(o).val();
                                            (n && !isNaN(n)) || (n = 0),
                                                (e += parseFloat(n));
                                        }
                                    ),
                                        $(".total-restock-items").text(e);
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-refund-payment-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t
                                                .closest(".modal-dialog")
                                                .find("form")
                                                .prop("action"),
                                            data: t
                                                .closest(".modal-dialog")
                                                .find("form")
                                                .serialize(),
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : e.data &&
                                                      e.data.refund_redirect_url
                                                    ? (window.location.href =
                                                          e.data.refund_redirect_url)
                                                    : ($(
                                                          "#main-order-content"
                                                      ).load(
                                                          window.location.href +
                                                              " #main-order-content > *"
                                                      ),
                                                      Cmat.showSuccess(
                                                          e.message
                                                      ),
                                                      t
                                                          .closest(".modal")
                                                          .modal("hide")),
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
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-update-shipping-status",
                                function (e) {
                                    e.preventDefault(),
                                        $(
                                            "#update-shipping-status-modal"
                                        ).modal("show");
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-update-shipping-status-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t
                                                .closest(".modal-dialog")
                                                .find("form")
                                                .prop("action"),
                                            data: t
                                                .closest(".modal-dialog")
                                                .find("form")
                                                .serialize(),
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : ($(
                                                          "#main-order-content"
                                                      ).load(
                                                          window.location.href +
                                                              " #main-order-content > *"
                                                      ),
                                                      Cmat.showSuccess(
                                                          e.message
                                                      ),
                                                      t
                                                          .closest(".modal")
                                                          .modal("hide")),
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
                                }
                            );
                    },
                },
            ]) && t(o.prototype, n),
            r && t(o, r),
            Object.defineProperty(o, "prototype", { writable: !1 }),
            e
        );
    })();
    $(document).ready(function () {
        new o().init();
    });
})();
