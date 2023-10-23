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
    function t(t, n) {
        for (var r = 0; r < n.length; r++) {
            var a = n[r];
            (a.enumerable = a.enumerable || !1),
                (a.configurable = !0),
                "value" in a && (a.writable = !0),
                Object.defineProperty(
                    t,
                    ((i = a.key),
                    (o = void 0),
                    (o = (function (t, n) {
                        if ("object" !== e(t) || null === t) return t;
                        var r = t[Symbol.toPrimitive];
                        if (void 0 !== r) {
                            var a = r.call(t, n || "default");
                            if ("object" !== e(a)) return a;
                            throw new TypeError(
                                "@@toPrimitive must return a primitive value."
                            );
                        }
                        return ("string" === n ? String : Number)(t);
                    })(i, "string")),
                    "symbol" === e(o) ? o : String(o)),
                    a
                );
        }
        var i, o;
    }
    var n = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function");
            })(this, e);
        }
        var n, r, a;
        return (
            (n = e),
            (r = [
                {
                    key: "init",
                    value: function () {
                        $(document).on(
                            "click",
                            ".btn-confirm-delete-region-item-modal-trigger",
                            function (e) {
                                e.preventDefault();
                                var t = $("#confirm-delete-region-item-modal");
                                t
                                    .find(".region-item-label")
                                    .text($(e.currentTarget).data("name")),
                                    t
                                        .find(
                                            "#confirm-delete-region-item-button"
                                        )
                                        .data(
                                            "id",
                                            $(e.currentTarget).data("id")
                                        ),
                                    t.modal("show");
                            }
                        ),
                            $(document).on(
                                "click",
                                "#confirm-delete-region-item-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            url: $(
                                                "div[data-delete-region-item-url]"
                                            ).data("delete-region-item-url"),
                                            data: {
                                                _method: "DELETE",
                                                id: t.data("id"),
                                            },
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : ($(
                                                          ".wrap-table-shipping-" +
                                                              t.data("id")
                                                      ).remove(),
                                                      Cmat.showSuccess(
                                                          e.message
                                                      )),
                                                    $(
                                                        "#confirm-delete-region-item-modal"
                                                    ).modal("hide");
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e);
                                            },
                                            complete: function () {
                                                t.removeClass("button-loading");
                                            },
                                        });
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-confirm-delete-price-item-modal-trigger",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(
                                        "#confirm-delete-price-item-modal"
                                    );
                                    t
                                        .find(".region-price-item-label")
                                        .text($(e.currentTarget).data("name")),
                                        t
                                            .find(
                                                "#confirm-delete-price-item-button"
                                            )
                                            .data(
                                                "id",
                                                $(e.currentTarget).data("id")
                                            ),
                                        t.modal("show");
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-delete-price-item-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            url: $(
                                                "div[data-delete-rule-item-url]"
                                            ).data("delete-rule-item-url"),
                                            data: {
                                                _method: "DELETE",
                                                id: t.data("id"),
                                            },
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : ($(
                                                          ".box-table-shipping-item-" +
                                                              t.data("id")
                                                      ).remove(),
                                                      0 === e.data.count &&
                                                          $(
                                                              ".wrap-table-shipping-" +
                                                                  e.data
                                                                      .shipping_id
                                                          ).remove(),
                                                      Cmat.showSuccess(
                                                          e.message
                                                      )),
                                                    $(
                                                        "#confirm-delete-price-item-modal"
                                                    ).modal("hide");
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e);
                                            },
                                            complete: function () {
                                                t.removeClass("button-loading");
                                            },
                                        });
                                }
                            );
                        var e = function (e, t, n, r) {
                            $(document)
                                .find(".field-has-error")
                                .removeClass("field-has-error");
                            var a = e;
                            a.addClass("button-loading");
                            var i = [];
                            "POST" !== n && (i._method = n),
                                $.each(t.serializeArray(), function (e, t) {
                                    ("from" !== t.name &&
                                        "to" !== t.name &&
                                        "price" !== t.name) ||
                                        (t.value &&
                                            (t.value = parseFloat(
                                                t.value.replace(",", "")
                                            ).toFixed(2))),
                                        (i[t.name] = t.value);
                                }),
                                r && (i.shipping_id = r),
                                (i = $.extend({}, i)),
                                $.ajax({
                                    type: "POST",
                                    url: t.prop("action"),
                                    data: i,
                                    success: function (e) {
                                        var t, n, i;
                                        if (e.error) Cmat.showError(e.message);
                                        else if (
                                            (Cmat.showSuccess(e.message),
                                            null != e &&
                                                null !== (t = e.data) &&
                                                void 0 !== t &&
                                                null !== (n = t.rule) &&
                                                void 0 !== n &&
                                                n.shipping_id &&
                                                null != e &&
                                                null !== (i = e.data) &&
                                                void 0 !== i &&
                                                i.html)
                                        ) {
                                            var o = $(
                                                    ".wrap-table-shipping-" +
                                                        e.data.rule
                                                            .shipping_id +
                                                        " .pd-all-20.border-bottom"
                                                ),
                                                l = o.find(
                                                    ".box-table-shipping-item-" +
                                                        e.data.rule.id
                                                );
                                            l.length
                                                ? l.replaceWith(e.data.html)
                                                : o.append(e.data.html),
                                                Cmat.initResources();
                                        }
                                        r && a.closest(".modal").modal("hide");
                                    },
                                    error: function (e) {
                                        Cmat.handleError(e);
                                    },
                                    complete: function () {
                                        a.removeClass("button-loading");
                                    },
                                });
                        };
                        function t(e, t, n) {
                            var r =
                                arguments.length > 3 && void 0 !== arguments[3]
                                    ? arguments[3]
                                    : {};
                            $.ajax({
                                type: "GET",
                                url: e,
                                data: r,
                                beforeSend: function () {
                                    n && n.addClass("button-loading"),
                                        t.addClass("table-loading");
                                },
                                success: function (e) {
                                    e.error
                                        ? Cmat.showError(e.message)
                                        : t.replaceWith(e.data.html);
                                },
                                error: function (e) {
                                    Cmat.handleError(e);
                                },
                                complete: function () {
                                    n && n.removeClass("button-loading");
                                },
                            });
                        }
                        $(document).on("click", ".btn-save-rule", function (t) {
                            t.preventDefault();
                            var n = $(t.currentTarget);
                            e(n, n.closest("form"), "PUT", null);
                        }),
                            $(document).on(
                                "change",
                                ".select-rule-type",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget),
                                        n = t.closest("form"),
                                        r = t.find("option:selected");
                                    r.data("show-from-to")
                                        ? n
                                              .find(".rule-from-to-inputs")
                                              .removeClass("d-none")
                                        : n
                                              .find(".rule-from-to-inputs")
                                              .addClass("d-none"),
                                        n
                                            .find(".unit-item-label")
                                            .text(r.data("unit")),
                                        n
                                            .find(".rule-from-to-label")
                                            .text(r.data("text"));
                                }
                            ),
                            $(document).on(
                                "keyup",
                                ".input-sync-item",
                                function (e) {
                                    var t = $(e.currentTarget),
                                        n = t.val();
                                    (n && !isNaN(n)) || (n = 0),
                                        t
                                            .closest(
                                                ".input-shipping-sync-wrapper"
                                            )
                                            .find(t.data("target"))
                                            .text(
                                                Cmat.numberFormat(
                                                    parseFloat(n),
                                                    2
                                                )
                                            );
                                }
                            ),
                            $(document).on(
                                "keyup",
                                ".input-sync-text-item",
                                function (e) {
                                    var t = $(e.currentTarget);
                                    t.closest(".input-shipping-sync-wrapper")
                                        .find(t.data("target"))
                                        .text(t.val());
                                }
                            ),
                            $(document).on(
                                "keyup",
                                ".input-to-value-field",
                                function (e) {
                                    var t = $(e.currentTarget),
                                        n = t.closest(
                                            ".input-shipping-sync-wrapper"
                                        );
                                    t.val()
                                        ? (n
                                              .find(".rule-to-value-wrap")
                                              .removeClass("hidden"),
                                          n
                                              .find(".rule-to-value-missing")
                                              .addClass("hidden"))
                                        : (n
                                              .find(".rule-to-value-wrap")
                                              .addClass("hidden"),
                                          n
                                              .find(".rule-to-value-missing")
                                              .removeClass("hidden"));
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-add-shipping-rule-trigger",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget),
                                        n = $("#add-shipping-rule-item-modal");
                                    $("#add-shipping-rule-item-button").data(
                                        "shipping-id",
                                        t.data("shipping-id")
                                    ),
                                        n
                                            .find(
                                                "select[name=type] option[disabled]"
                                            )
                                            .prop("disabled", !1),
                                        t.data("country") ||
                                            n
                                                .find(
                                                    "select[name=type] option[value=base_on_zip_code]"
                                                )
                                                .prop("disabled", !0),
                                        n.find("input[name=name]").val(""),
                                        n
                                            .find("select[name=type]")
                                            .val("")
                                            .trigger("change"),
                                        n.find("input[name=from]").val("0"),
                                        n.find("input[name=to]").val(""),
                                        n.find("input[name=price]").val("0"),
                                        n.modal("show");
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-shipping-rule-item-trigger",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget),
                                        n = $(
                                            "#form-shipping-rule-item-detail-modal"
                                        );
                                    n.modal("show"),
                                        $.ajax({
                                            type: "GET",
                                            url: t.data("url"),
                                            beforeSend: function () {
                                                n
                                                    .find(".modal-title strong")
                                                    .html(""),
                                                    n
                                                        .find(".modal-body")
                                                        .html(
                                                            '<div class="w-100 text-center py-3"><div class="spinner-border" role="status">\n                    <span class="visually-hidden">Loading...</span>\n                  </div></div>'
                                                        );
                                            },
                                            success: function (e) {
                                                e.error
                                                    ? Cmat.showError(e.message)
                                                    : (n
                                                          .find(".modal-body")
                                                          .html(e.data.html),
                                                      n
                                                          .find(
                                                              ".modal-title strong"
                                                          )
                                                          .html(e.message),
                                                      Cmat.initResources());
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e);
                                            },
                                        });
                                }
                            ),
                            $(document).on(
                                "click",
                                "#save-shipping-rule-item-detail-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget),
                                        n = $(
                                            "#form-shipping-rule-item-detail-modal"
                                        ),
                                        r = n.find("form");
                                    $.ajax({
                                        type: r.prop("method"),
                                        url: r.prop("action"),
                                        data: r.serialize(),
                                        beforeSend: function () {
                                            t.addClass("button-loading");
                                        },
                                        success: function (e) {
                                            if (e.error)
                                                Cmat.showError(e.message);
                                            else {
                                                var t = $(
                                                    ".table-shipping-rule-" +
                                                        e.data.shipping_rule_id
                                                );
                                                t.find(
                                                    ".shipping-rule-item-" +
                                                        e.data.id
                                                ).length
                                                    ? t
                                                          .find(
                                                              ".shipping-rule-item-" +
                                                                  e.data.id
                                                          )
                                                          .replaceWith(
                                                              e.data.html
                                                          )
                                                    : t.prepend(e.data.html),
                                                    n.modal("hide"),
                                                    Cmat.showSuccess(e.message);
                                            }
                                        },
                                        error: function (e) {
                                            Cmat.handleError(e);
                                        },
                                        complete: function () {
                                            t.removeClass("button-loading");
                                        },
                                    });
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-confirm-delete-rule-item-modal-trigger",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(
                                        "#confirm-delete-shipping-rule-item-modal"
                                    );
                                    t
                                        .find(".item-label")
                                        .text($(e.currentTarget).data("name")),
                                        t
                                            .find(
                                                "#confirm-delete-shipping-rule-item-button"
                                            )
                                            .data(
                                                "url",
                                                $(e.currentTarget).data(
                                                    "section"
                                                )
                                            ),
                                        t.modal("show");
                                }
                            ),
                            $(document).on(
                                "click",
                                "#confirm-delete-shipping-rule-item-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            url: t.data("url"),
                                            data: { _method: "DELETE" },
                                            success: function (e) {
                                                if (e.error)
                                                    Cmat.showError(e.message);
                                                else {
                                                    var t = $(
                                                        ".table-shipping-rule-" +
                                                            e.data
                                                                .shipping_rule_id
                                                    );
                                                    t.find(
                                                        ".shipping-rule-item-" +
                                                            e.data.id
                                                    ).length &&
                                                        t
                                                            .find(
                                                                ".shipping-rule-item-" +
                                                                    e.data.id
                                                            )
                                                            .fadeOut(
                                                                500,
                                                                function () {
                                                                    $(
                                                                        this
                                                                    ).remove();
                                                                }
                                                            ),
                                                        Cmat.showSuccess(
                                                            e.message
                                                        );
                                                }
                                                $(
                                                    "#confirm-delete-shipping-rule-item-modal"
                                                ).modal("hide");
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e);
                                            },
                                            complete: function () {
                                                t.removeClass("button-loading");
                                            },
                                        });
                                }
                            ),
                            $(document)
                                .find(".select-country-search")
                                .select2({
                                    width: "100%",
                                    dropdownParent: $("#select-country-modal"),
                                }),
                            $(document).on(
                                "click",
                                ".btn-select-country",
                                function (e) {
                                    e.preventDefault(),
                                        $("#select-country-modal").modal(
                                            "show"
                                        );
                                }
                            ),
                            $(document).on(
                                "click",
                                "#add-shipping-region-button",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget);
                                    t.addClass("button-loading");
                                    var n = t
                                        .closest(".modal-content")
                                        .find("form");
                                    $.ajax({
                                        type: "POST",
                                        url: n.prop("action"),
                                        data: n.serialize(),
                                        success: function (e) {
                                            e.error
                                                ? Cmat.showError(e.message)
                                                : (Cmat.showSuccess(e.message),
                                                  $(".wrapper-content").load(
                                                      window.location.href +
                                                          " .wrapper-content > *"
                                                  )),
                                                t.removeClass("button-loading"),
                                                $(
                                                    "#select-country-modal"
                                                ).modal("hide");
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
                                "#add-shipping-rule-item-button",
                                function (t) {
                                    t.preventDefault(),
                                        e(
                                            $(t.currentTarget),
                                            $(t.currentTarget)
                                                .closest(".modal-content")
                                                .find("form"),
                                            "POST",
                                            $(t.currentTarget).data(
                                                "shipping-id"
                                            )
                                        );
                                }
                            ),
                            $(document).on(
                                "keyup",
                                ".base-price-rule-item",
                                function (e) {
                                    var t = $(e.currentTarget).val();
                                    (t && !isNaN(t)) || (t = 0),
                                        $.each(
                                            $(document).find(
                                                ".support-shipping .rule-adjustment-price-item"
                                            ),
                                            function (e, n) {
                                                var r = $(n)
                                                    .closest("tr")
                                                    .find(
                                                        ".shipping-price-district"
                                                    )
                                                    .val();
                                                (r && !isNaN(r)) || (r = 0),
                                                    $(n).text(
                                                        Cmat.numberFormat(
                                                            parseFloat(t) +
                                                                parseFloat(r)
                                                        ),
                                                        2
                                                    );
                                            }
                                        );
                                }
                            ),
                            $(document).on(
                                "change",
                                "select[name=shipping_rule_id].shipping-rule-id",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget),
                                        n = t.closest("form"),
                                        r = n.find(
                                            'select[data-type="country"]'
                                        ),
                                        a = t
                                            .find("option:selected")
                                            .data("country");
                                    r.length
                                        ? r.val() != a &&
                                          r.val(a).trigger("change")
                                        : (r = n.find('input[name="country"]'))
                                              .length &&
                                          r.val() != a &&
                                          r.val(a);
                                }
                            ),
                            $(document).on(
                                "click",
                                ".table-shipping-rule-items .shipping-rule-load-items",
                                function (e) {
                                    e.preventDefault();
                                    var n = $(e.currentTarget),
                                        r = n.closest(
                                            ".table-shipping-rule-items"
                                        );
                                    t(n.attr("href"), r, n);
                                }
                            ),
                            $(document).on(
                                "click",
                                ".table-shipping-rule-items a.page-link",
                                function (e) {
                                    e.preventDefault();
                                    var n = $(e.currentTarget),
                                        r = n.closest(
                                            ".table-shipping-rule-items"
                                        );
                                    t(n.attr("href"), r, n);
                                }
                            ),
                            $(document).on(
                                "change",
                                ".table-shipping-rule-items .number-record .numb",
                                function (e) {
                                    e.preventDefault();
                                    var n = $(e.currentTarget),
                                        r = n.val();
                                    if (!isNaN(r) && r > 0) {
                                        var a = n.closest(
                                                ".table-shipping-rule-items"
                                            ),
                                            i = a.find(
                                                "thead tr th[data-column][data-dir]"
                                            ),
                                            o = { per_page: r };
                                        i.length &&
                                            ((o.order_by = i.data("column")),
                                            (o.order_dir =
                                                i.data("dir") || "DESC")),
                                            t(a.data("url"), a, n, o);
                                    } else
                                        n.val(n.attr("min") || 12).trigger(
                                            "change"
                                        );
                                }
                            ),
                            $(document).on(
                                "click",
                                ".table-shipping-rule-items thead tr th[data-column]",
                                function (e) {
                                    e.preventDefault();
                                    var n = $(e.currentTarget),
                                        r = n.data("column"),
                                        a = n.data("dir") || "ASC";
                                    a = "ASC" == a ? "DESC" : "ASC";
                                    var i = n.closest(
                                            ".table-shipping-rule-items"
                                        ),
                                        o = i
                                            .find(".number-record .numb")
                                            .val();
                                    t(i.data("url"), i, n, {
                                        order_by: r,
                                        order_dir: a,
                                        per_page: o,
                                    });
                                }
                            );
                    },
                },
            ]),
            r && t(n.prototype, r),
            a && t(n, a),
            Object.defineProperty(n, "prototype", { writable: !1 }),
            e
        );
    })();
    $(function () {
        new n().init();
    });
})();
