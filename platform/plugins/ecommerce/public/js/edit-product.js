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
    function t(t, a) {
        for (var r = 0; r < a.length; r++) {
            var n = a[r];
            (n.enumerable = n.enumerable || !1),
                (n.configurable = !0),
                "value" in n && (n.writable = !0),
                Object.defineProperty(
                    t,
                    ((o = n.key),
                    (i = void 0),
                    (i = (function (t, a) {
                        if ("object" !== e(t) || null === t) return t;
                        var r = t[Symbol.toPrimitive];
                        if (void 0 !== r) {
                            var n = r.call(t, a || "default");
                            if ("object" !== e(n)) return n;
                            throw new TypeError(
                                "@@toPrimitive must return a primitive value."
                            );
                        }
                        return ("string" === a ? String : Number)(t);
                    })(o, "string")),
                    "symbol" === e(i) ? i : String(i)),
                    n
                );
        }
        var o, i;
    }
    var a = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function");
            })(this, e),
                (this.$body = $("body")),
                this.initElements(),
                this.handleEvents(),
                this.handleChangeSaleType(),
                this.handleShipping(),
                this.handleStorehouse(),
                this.handleModifyAttributeSets(),
                this.handleAddVariations(),
                this.handleDeleteVariations();
        }
        var a, r, n;
        return (
            (a = e),
            (r = [
                {
                    key: "handleEvents",
                    value: function () {
                        var e = this;
                        e.$body.on("click", ".select-all", function (e) {
                            e.preventDefault();
                            var t = $($(e.currentTarget).attr("href"));
                            t.find("option").attr("selected", !0),
                                t.trigger("change");
                        }),
                            e.$body.on("click", ".deselect-all", function (e) {
                                e.preventDefault();
                                var t = $($(e.currentTarget).attr("href"));
                                t.find("option").removeAttr("selected"),
                                    t.trigger("change");
                            }),
                            e.$body.on(
                                "change",
                                "#attribute_sets",
                                function (e) {
                                    var t = $("#attribute_set_group"),
                                        a = $(e.currentTarget).val();
                                    t.find(".panel").hide(),
                                        a &&
                                            _.forEach(a, function (e) {
                                                t.find(
                                                    '.panel[data-id="' +
                                                        e +
                                                        '"]'
                                                ).show();
                                            }),
                                        $(".select2-select").select2();
                                }
                            ),
                            $("#attribute_sets").trigger("change"),
                            e.$body.on(
                                "change",
                                ".is-variation-default input",
                                function (e) {
                                    var t = $(e.currentTarget),
                                        a = t.is(":checked");
                                    $(".is-variation-default input").prop(
                                        "checked",
                                        !1
                                    ),
                                        a && t.prop("checked", !0);
                                }
                            ),
                            $(document).on(
                                "change",
                                ".table-check-all",
                                function (e) {
                                    var t = $(e.currentTarget),
                                        a = t.attr("data-set"),
                                        r = t.prop("checked");
                                    $(a).each(function (e, t) {
                                        r
                                            ? ($(t).prop("checked", !0),
                                              $(
                                                  ".btn-trigger-delete-selected-variations"
                                              ).show())
                                            : ($(t).prop("checked", !1),
                                              $(
                                                  ".btn-trigger-delete-selected-variations"
                                              ).hide());
                                    });
                                }
                            ),
                            $(document).on(
                                "change",
                                ".checkboxes",
                                function (e) {
                                    var t = $(e.currentTarget).closest(
                                            ".table-hover-variants"
                                        ),
                                        a = [];
                                    t
                                        .find(".checkboxes:checked")
                                        .each(function (e, t) {
                                            a[e] = $(t).val();
                                        }),
                                        a.length > 0
                                            ? $(
                                                  ".btn-trigger-delete-selected-variations"
                                              ).show()
                                            : $(
                                                  ".btn-trigger-delete-selected-variations"
                                              ).hide(),
                                        a.length !==
                                        t.find(".checkboxes").length
                                            ? t
                                                  .find(".table-check-all")
                                                  .prop("checked", !1)
                                            : t
                                                  .find(".table-check-all")
                                                  .prop("checked", !0);
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-delete-selected-variations",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget),
                                        a = [];
                                    if (
                                        ($(".table-hover-variants")
                                            .find(".checkboxes:checked")
                                            .each(function (e, t) {
                                                a[e] = $(t).val();
                                            }),
                                        0 === a.length)
                                    )
                                        return (
                                            Cmat.showError(
                                                CmatVariables.languages.tables
                                                    .please_select_record
                                            ),
                                            !1
                                        );
                                    $(
                                        "#delete-selected-variations-button"
                                    ).data("href", t.data("target")),
                                        $("#delete-variations-modal").modal(
                                            "show"
                                        );
                                }
                            ),
                            $("#delete-selected-variations-button")
                                .off("click")
                                .on("click", function (t) {
                                    t.preventDefault();
                                    var a = $(t.currentTarget);
                                    a.addClass("button-loading");
                                    var r = $(".table-hover-variants"),
                                        n = [];
                                    r
                                        .find(".checkboxes:checked")
                                        .each(function (e, t) {
                                            n[e] = $(t).val();
                                        }),
                                        $.ajax({
                                            url: a.data("href"),
                                            type: "POST",
                                            data: { _method: "DELETE", ids: n },
                                            success: function (t) {
                                                t.error
                                                    ? Cmat.showError(t.message)
                                                    : Cmat.showSuccess(
                                                          t.message
                                                      ),
                                                    $(
                                                        ".btn-trigger-delete-selected-variations"
                                                    ).hide(),
                                                    r
                                                        .find(
                                                            ".table-check-all"
                                                        )
                                                        .prop("checked", !1),
                                                    a
                                                        .closest(".modal")
                                                        .modal("hide"),
                                                    a.removeClass(
                                                        "button-loading"
                                                    ),
                                                    r.find("tbody tr")
                                                        .length === n.length
                                                        ? $(
                                                              "#main-manage-product-type"
                                                          ).load(
                                                              window.location
                                                                  .href +
                                                                  " #main-manage-product-type > *",
                                                              function () {
                                                                  e.initElements(),
                                                                      e.handleEvents();
                                                              }
                                                          )
                                                        : n.forEach(function (
                                                              e
                                                          ) {
                                                              r.find(
                                                                  "#variation-id-" +
                                                                      e
                                                              )
                                                                  .fadeOut(400)
                                                                  .remove();
                                                          });
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e),
                                                    a.removeClass(
                                                        "button-loading"
                                                    );
                                            },
                                        });
                                });
                    },
                },
                {
                    key: "initElements",
                    value: function () {
                        $(".select2-select").select2(),
                            $(".form-date-time").datetimepicker({
                                format: "YYYY-MM-DD HH:mm:ss",
                                toolbarPlacement: "bottom",
                                showTodayButton: !0,
                                stepping: 1,
                            }),
                            $("#attribute_set_group .panel-collapse").on(
                                "shown.bs.collapse",
                                function () {
                                    $(".select2-select").select2();
                                }
                            ),
                            $('a[data-bs-toggle="tab"]').on(
                                "shown.bs.tab",
                                function () {
                                    $(".select2-select").select2();
                                }
                            );
                    },
                },
                {
                    key: "handleChangeSaleType",
                    value: function () {
                        this.$body.on(
                            "click",
                            ".turn-on-schedule",
                            function (e) {
                                e.preventDefault();
                                var t = $(e.currentTarget),
                                    a = t.closest(".price-group");
                                t.addClass("hidden"),
                                    a
                                        .find(".turn-off-schedule")
                                        .removeClass("hidden"),
                                    a.find(".detect-schedule").val(1),
                                    a
                                        .find(".scheduled-time")
                                        .removeClass("hidden");
                            }
                        ),
                            this.$body.on(
                                "click",
                                ".turn-off-schedule",
                                function (e) {
                                    e.preventDefault();
                                    var t = $(e.currentTarget),
                                        a = t.closest(".price-group");
                                    t.addClass("hidden"),
                                        a
                                            .find(".turn-on-schedule")
                                            .removeClass("hidden"),
                                        a.find(".detect-schedule").val(0),
                                        a
                                            .find(".scheduled-time")
                                            .addClass("hidden");
                                }
                            );
                    },
                },
                {
                    key: "handleStorehouse",
                    value: function () {
                        this.$body.on(
                            "click",
                            "input.storehouse-management-status",
                            function (e) {
                                var t = $(".storehouse-info");
                                !0 === $(e.currentTarget).prop("checked")
                                    ? (t.removeClass("hidden"),
                                      $(".stock-status-wrapper").addClass(
                                          "hidden"
                                      ))
                                    : (t.addClass("hidden"),
                                      $(".stock-status-wrapper").removeClass(
                                          "hidden"
                                      ));
                            }
                        );
                    },
                },
                {
                    key: "handleShipping",
                    value: function () {
                        this.$body.on(
                            "click",
                            ".change-measurement .dropdown-menu a",
                            function (e) {
                                e.preventDefault();
                                var t = $(e.currentTarget),
                                    a = t.closest(".change-measurement");
                                a
                                    .find("input[type=hidden]")
                                    .val(t.attr("data-alias")),
                                    a
                                        .find(".dropdown-toggle .alias")
                                        .html(t.attr("data-alias"));
                            }
                        );
                    },
                },
                {
                    key: "handleModifyAttributeSets",
                    value: function () {
                        var e = this;
                        e.$body.on(
                            "click",
                            "#store-related-attributes-button",
                            function (t) {
                                t.preventDefault();
                                var a = $(t.currentTarget),
                                    r = [];
                                a
                                    .closest(".modal-content")
                                    .find(".attribute-set-item:checked")
                                    .each(function (e, t) {
                                        r[e] = $(t).val();
                                    }),
                                    $.ajax({
                                        url: a.data("target"),
                                        type: "POST",
                                        data: { attribute_sets: r },
                                        beforeSend: function () {
                                            a.addClass("button-loading");
                                        },
                                        success: function (t) {
                                            t.error
                                                ? Cmat.showError(t.message)
                                                : (Cmat.showSuccess(t.message),
                                                  $(
                                                      "#main-manage-product-type"
                                                  ).load(
                                                      window.location.href +
                                                          " #main-manage-product-type > *",
                                                      function () {
                                                          e.initElements(),
                                                              e.handleEvents();
                                                      }
                                                  ),
                                                  $(
                                                      "#select-attribute-sets-modal"
                                                  ).modal("hide")),
                                                a.removeClass("button-loading");
                                        },
                                        complete: function () {
                                            a.removeClass("button-loading");
                                        },
                                        error: function (e) {
                                            Cmat.handleError(e),
                                                a.removeClass("button-loading");
                                        },
                                    });
                            }
                        );
                    },
                },
                {
                    key: "handleAddVariations",
                    value: function () {
                        var e = this,
                            t = function (t) {
                                var a = t
                                        .closest(".modal-content")
                                        .find(".variation-form-wrapper form"),
                                    r = new FormData(a[0]);
                                jQuery().inputmask &&
                                    a
                                        .find("input.input-mask-number")
                                        .map(function (e, t) {
                                            var a = $(t);
                                            a.inputmask &&
                                                r.append(
                                                    a.attr("name"),
                                                    a.inputmask("unmaskedvalue")
                                                );
                                        }),
                                    $.ajax({
                                        url: t.data("target"),
                                        type: "POST",
                                        data: r,
                                        processData: !1,
                                        contentType: !1,
                                        beforeSend: function () {
                                            t.addClass("button-loading");
                                        },
                                        success: function (a) {
                                            a.error
                                                ? Cmat.showError(a.message)
                                                : (Cmat.showSuccess(a.message),
                                                  t
                                                      .closest(".modal.fade")
                                                      .modal("hide"),
                                                  $(
                                                      "#product-variations-wrapper"
                                                  ).load(
                                                      window.location.href +
                                                          " #product-variations-wrapper > *",
                                                      function () {
                                                          e.initElements(),
                                                              e.handleEvents();
                                                      }
                                                  ),
                                                  t
                                                      .closest(".modal-content")
                                                      .find(
                                                          ".variation-form-wrapper"
                                                      )
                                                      .remove()),
                                                t.removeClass("button-loading");
                                        },
                                        complete: function () {
                                            t.removeClass("button-loading");
                                        },
                                        error: function (e) {
                                            Cmat.handleError(e),
                                                t.removeClass("button-loading");
                                        },
                                    });
                            };
                        e.$body.on(
                            "click",
                            "#store-product-variation-button",
                            function (e) {
                                e.preventDefault(), t($(e.currentTarget));
                            }
                        ),
                            e.$body.on(
                                "click",
                                "#update-product-variation-button",
                                function (e) {
                                    e.preventDefault(), t($(e.currentTarget));
                                }
                            ),
                            $("#add-new-product-variation-modal").on(
                                "hidden.bs.modal",
                                function (e) {
                                    $(this)
                                        .find(
                                            ".modal-content .variation-form-wrapper"
                                        )
                                        .remove();
                                }
                            ),
                            $("#edit-product-variation-modal").on(
                                "hidden.bs.modal",
                                function (e) {
                                    $(this)
                                        .find(
                                            ".modal-content .variation-form-wrapper"
                                        )
                                        .remove();
                                }
                            ),
                            e.$body.on(
                                "click",
                                "#generate-all-versions-button",
                                function (t) {
                                    t.preventDefault();
                                    var a = $(t.currentTarget);
                                    $.ajax({
                                        url: a.data("target"),
                                        type: "POST",
                                        beforeSend: function () {
                                            a.addClass("button-loading");
                                        },
                                        success: function (t) {
                                            t.error
                                                ? Cmat.showError(t.message)
                                                : (Cmat.showSuccess(t.message),
                                                  $(
                                                      "#generate-all-versions-modal"
                                                  ).modal("hide"),
                                                  $(
                                                      "#product-variations-wrapper"
                                                  ).load(
                                                      window.location.href +
                                                          " #product-variations-wrapper > *",
                                                      function () {
                                                          e.initElements(),
                                                              e.handleEvents();
                                                      }
                                                  )),
                                                a.removeClass("button-loading");
                                        },
                                        complete: function () {
                                            a.removeClass("button-loading");
                                        },
                                        error: function (e) {
                                            Cmat.handleError(e),
                                                a.removeClass("button-loading");
                                        },
                                    });
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-add-new-product-variation",
                                function (t) {
                                    t.preventDefault();
                                    var a = $(t.currentTarget);
                                    $(
                                        "#add-new-product-variation-modal .modal-body .loading-spinner"
                                    ).show(),
                                        $(
                                            "#add-new-product-variation-modal .modal-body .variation-form-wrapper"
                                        ).remove(),
                                        $(
                                            "#add-new-product-variation-modal"
                                        ).modal("show"),
                                        $.ajax({
                                            url: a.data("load-form"),
                                            type: "GET",
                                            success: function (t) {
                                                t.error
                                                    ? Cmat.showError(t.message)
                                                    : ($(
                                                          "#add-new-product-variation-modal .modal-body .loading-spinner"
                                                      ).hide(),
                                                      $(
                                                          "#add-new-product-variation-modal .modal-body"
                                                      ).append(t.data),
                                                      e.initElements(),
                                                      Cmat.initResources(),
                                                      $(
                                                          "#store-product-variation-button"
                                                      ).data(
                                                          "target",
                                                          a.data("target")
                                                      ),
                                                      $(
                                                          ".list-gallery-media-images"
                                                      ).each(function (e, t) {
                                                          var a = $(t);
                                                          a.data(
                                                              "ui-sortable"
                                                          ) &&
                                                              a.sortable(
                                                                  "destroy"
                                                              ),
                                                              a.sortable();
                                                      }));
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e);
                                            },
                                        });
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-edit-product-version",
                                function (t) {
                                    t.preventDefault(),
                                        $(
                                            "#update-product-variation-button"
                                        ).data(
                                            "target",
                                            $(t.currentTarget).data("target")
                                        );
                                    var a = $(t.currentTarget);
                                    $(
                                        "#edit-product-variation-modal .modal-body .loading-spinner"
                                    ).show(),
                                        $(
                                            "#edit-product-variation-modal .modal-body .variation-form-wrapper"
                                        ).remove(),
                                        $(
                                            "#edit-product-variation-modal"
                                        ).modal("show"),
                                        $.ajax({
                                            url: a.data("load-form"),
                                            type: "GET",
                                            success: function (t) {
                                                t.error
                                                    ? Cmat.showError(t.message)
                                                    : ($(
                                                          "#edit-product-variation-modal .modal-body .loading-spinner"
                                                      ).hide(),
                                                      $(
                                                          "#edit-product-variation-modal .modal-body"
                                                      ).append(t.data),
                                                      e.initElements(),
                                                      Cmat.initResources(),
                                                      $(
                                                          ".list-gallery-media-images"
                                                      ).each(function (e, t) {
                                                          var a = $(t);
                                                          a.data(
                                                              "ui-sortable"
                                                          ) &&
                                                              a.sortable(
                                                                  "destroy"
                                                              ),
                                                              a.sortable();
                                                      }));
                                            },
                                            error: function (e) {
                                                Cmat.handleError(e);
                                            },
                                        });
                                }
                            ),
                            e.$body.on(
                                "click",
                                ".btn-trigger-add-attribute-to-simple-product",
                                function (t) {
                                    t.preventDefault();
                                    var a = $(t.currentTarget),
                                        r = [],
                                        n = [];
                                    $.each(
                                        $(
                                            ".list-product-attribute-wrap-detail .product-attribute-set-item"
                                        ),
                                        function (e, t) {
                                            $(t).hasClass("hidden") ||
                                                ("" !==
                                                    $(t)
                                                        .find(
                                                            ".product-select-attribute-item-value"
                                                        )
                                                        .val() &&
                                                    (r.push(
                                                        $(t)
                                                            .find(
                                                                ".product-select-attribute-item-value"
                                                            )
                                                            .val()
                                                    ),
                                                    n.push(
                                                        $(t)
                                                            .find(
                                                                ".product-select-attribute-item-value"
                                                            )
                                                            .data("set-id")
                                                    )));
                                        }
                                    ),
                                        r.length &&
                                            $.ajax({
                                                url: a.data("target"),
                                                type: "POST",
                                                data: {
                                                    added_attributes: r,
                                                    added_attribute_sets: n,
                                                },
                                                beforeSend: function () {
                                                    a.addClass(
                                                        "button-loading"
                                                    );
                                                },
                                                success: function (t) {
                                                    t.error
                                                        ? Cmat.showError(
                                                              t.message
                                                          )
                                                        : ($(
                                                              "#main-manage-product-type"
                                                          ).load(
                                                              window.location
                                                                  .href +
                                                                  " #main-manage-product-type > *",
                                                              function () {
                                                                  e.initElements(),
                                                                      e.handleEvents();
                                                              }
                                                          ),
                                                          $(
                                                              "#confirm-delete-version-modal"
                                                          ).modal("hide"),
                                                          Cmat.showSuccess(
                                                              t.message
                                                          )),
                                                        a.removeClass(
                                                            "button-loading"
                                                        );
                                                },
                                                complete: function () {
                                                    a.removeClass(
                                                        "button-loading"
                                                    );
                                                },
                                                error: function (e) {
                                                    a.removeClass(
                                                        "button-loading"
                                                    ),
                                                        Cmat.handleError(e);
                                                },
                                            });
                                }
                            );
                    },
                },
                {
                    key: "handleDeleteVariations",
                    value: function () {
                        var e = this;
                        $(document).on(
                            "click",
                            ".btn-trigger-delete-version",
                            function (e) {
                                e.preventDefault(),
                                    $("#delete-version-button")
                                        .data(
                                            "target",
                                            $(e.currentTarget).data("target")
                                        )
                                        .data(
                                            "id",
                                            $(e.currentTarget).data("id")
                                        ),
                                    $("#confirm-delete-version-modal").modal(
                                        "show"
                                    );
                            }
                        ),
                            e.$body.on(
                                "click",
                                "#delete-version-button",
                                function (t) {
                                    t.preventDefault();
                                    var a = $(t.currentTarget);
                                    $.ajax({
                                        url: a.data("target"),
                                        type: "POST",
                                        beforeSend: function () {
                                            a.addClass("button-loading");
                                        },
                                        success: function (t) {
                                            if (t.error)
                                                Cmat.showError(t.message);
                                            else {
                                                var r = $(
                                                    ".table-hover-variants"
                                                );
                                                1 === r.find("tbody tr").length
                                                    ? $(
                                                          "#main-manage-product-type"
                                                      ).load(
                                                          window.location.href +
                                                              " #main-manage-product-type > *",
                                                          function () {
                                                              e.initElements(),
                                                                  e.handleEvents();
                                                          }
                                                      )
                                                    : r
                                                          .find(
                                                              "#variation-id-" +
                                                                  a.data("id")
                                                          )
                                                          .fadeOut(400)
                                                          .remove(),
                                                    $(
                                                        "#confirm-delete-version-modal"
                                                    ).modal("hide"),
                                                    Cmat.showSuccess(t.message);
                                            }
                                            a.removeClass("button-loading");
                                        },
                                        complete: function () {
                                            a.removeClass("button-loading");
                                        },
                                        error: function (e) {
                                            a.removeClass("button-loading"),
                                                Cmat.handleError(e);
                                        },
                                    });
                                }
                            );
                    },
                },
            ]) && t(a.prototype, r),
            n && t(a, n),
            Object.defineProperty(a, "prototype", { writable: !1 }),
            e
        );
    })();
    $(window).on("load", function () {
        new a(),
            $("body").on(
                "click",
                ".list-gallery-media-images .btn_remove_image",
                function (e) {
                    e.preventDefault(),
                        $(e.currentTarget).closest("li").remove();
                }
            ),
            $(document).on(
                "click",
                ".btn-trigger-select-product-attributes",
                function (e) {
                    e.preventDefault(),
                        $("#store-related-attributes-button").data(
                            "target",
                            $(e.currentTarget).data("target")
                        ),
                        $("#select-attribute-sets-modal").modal("show");
                }
            ),
            $(document).on(
                "click",
                ".btn-trigger-generate-all-versions",
                function (e) {
                    e.preventDefault(),
                        $("#generate-all-versions-button").data(
                            "target",
                            $(e.currentTarget).data("target")
                        ),
                        $("#generate-all-versions-modal").modal("show");
                }
            ),
            $(document).on("click", ".btn-trigger-add-attribute", function (e) {
                e.preventDefault(),
                    $(".list-product-attribute-wrap").toggleClass("hidden"),
                    $(e.currentTarget).toggleClass("adding_attribute_enable"),
                    $(e.currentTarget).hasClass("adding_attribute_enable")
                        ? $("#is_added_attributes").val(1)
                        : $("#is_added_attributes").val(0);
                var t = $(e.currentTarget).data("toggle-text");
                $(e.currentTarget).data(
                    "toggle-text",
                    $(e.currentTarget).text()
                ),
                    $(e.currentTarget).text(t);
            }),
            $(document).on(
                "change",
                ".product-select-attribute-item",
                function () {
                    var e = [];
                    $.each(
                        $(".product-select-attribute-item"),
                        function (t, a) {
                            "" !== $(a).val() && e.push(t);
                        }
                    ),
                        e.length
                            ? $(
                                  ".btn-trigger-add-attribute-to-simple-product"
                              ).removeClass("hidden")
                            : $(
                                  ".btn-trigger-add-attribute-to-simple-product"
                              ).addClass("hidden");
                }
            );
        var e,
            t = function () {
                $.each(
                    $(
                        ".product-attribute-set-item:visible .product-select-attribute-item option"
                    ),
                    function (e, t) {
                        $(t).prop("value") !== $(t).closest("select").val() &&
                            (0 ===
                            $(
                                ".list-product-attribute-wrap-detail .product-select-attribute-item-value-id-" +
                                    $(t).prop("value")
                            ).length
                                ? $(t).prop("disabled", !1)
                                : $(t).prop("disabled", !0));
                    }
                );
            };
        $(document).on(
            "change",
            ".product-select-attribute-item",
            function (e) {
                $(e.currentTarget)
                    .closest(".product-attribute-set-item")
                    .find(".product-select-attribute-item-value-wrap")
                    .html(
                        $(
                            ".list-product-attribute-values-wrap .product-select-attribute-item-value-wrap-" +
                                $(e.currentTarget).val()
                        ).html()
                    ),
                    $(e.currentTarget)
                        .closest(".product-attribute-set-item")
                        .find(
                            ".product-select-attribute-item-value-id-" +
                                $(e.currentTarget).val()
                        )
                        .prop(
                            "name",
                            "added_attributes[" + $(e.currentTarget).val() + "]"
                        ),
                    t();
            }
        ),
            $(document).on(
                "click",
                ".btn-trigger-add-attribute-item",
                function (e) {
                    e.preventDefault();
                    var a = $(
                            ".list-product-attribute-values-wrap .product-select-attribute-item-template"
                        ),
                        r = null;
                    $.each(
                        $(
                            ".product-attribute-set-item:visible .product-select-attribute-item option"
                        ),
                        function (e, t) {
                            $(t).prop("value") !==
                                $(t).closest("select").val() &&
                                !1 === $(t).prop("disabled") &&
                                (a
                                    .find(
                                        ".product-select-attribute-item-value-wrap"
                                    )
                                    .html(
                                        $(
                                            ".list-product-attribute-values-wrap .product-select-attribute-item-value-wrap-" +
                                                $(t).prop("value")
                                        ).html()
                                    ),
                                (r = $(t).prop("value")));
                        }
                    );
                    var n = $(".list-product-attribute-wrap-detail");
                    n.append(a.html()),
                        n
                            .find(
                                ".product-attribute-set-item:last-child .product-select-attribute-item"
                            )
                            .val(r),
                        n
                            .find(
                                ".product-select-attribute-item-value-id-" + r
                            )
                            .prop("name", "added_attributes[" + r + "]"),
                        n.find(".product-attribute-set-item").length ===
                            $(
                                ".list-product-attribute-values-wrap .product-select-attribute-item-wrap-template"
                            ).length && $(e.currentTarget).addClass("hidden"),
                        $(".product-set-item-delete-action").removeClass(
                            "hidden"
                        ),
                        t();
                }
            ),
            $(document).on(
                "click",
                ".product-set-item-delete-action a",
                function (e) {
                    e.preventDefault(),
                        $(e.currentTarget)
                            .closest(".product-attribute-set-item")
                            .remove();
                    var a = $(".list-product-attribute-wrap-detail");
                    a.find(".product-attribute-set-item").length < 2 &&
                        $(".product-set-item-delete-action").addClass("hidden"),
                        a.find(".product-attribute-set-item").length <
                            $(
                                ".list-product-attribute-values-wrap .product-select-attribute-item-wrap-template"
                            ).length &&
                            $(".btn-trigger-add-attribute-item").removeClass(
                                "hidden"
                            ),
                        t();
                }
            ),
            "undefined" != typeof RvMediaStandAlone &&
                new RvMediaStandAlone(
                    ".images-wrapper .btn-trigger-edit-product-image",
                    {
                        filter: "image",
                        view_in: "all_media",
                        onSelectFiles: function (e, t) {
                            var a = _.first(e),
                                r = t
                                    .closest(".product-image-item-handler")
                                    .find(".image-box"),
                                n = t.closest(".list-gallery-media-images");
                            r.find(".image-data").val(a.url),
                                r
                                    .find(".preview_image")
                                    .attr("src", a.thumb)
                                    .show(),
                                _.forEach(e, function (e, t) {
                                    if (t) {
                                        var a = $(document)
                                                .find(
                                                    "#product_select_image_template"
                                                )
                                                .html()
                                                .replace(
                                                    /__name__/gi,
                                                    r
                                                        .find(".image-data")
                                                        .attr("name")
                                                ),
                                            o = $(
                                                '<li class="product-image-item-handler">' +
                                                    a +
                                                    "</li>"
                                            );
                                        o.find(".image-data").val(e.url),
                                            o
                                                .find(".preview_image")
                                                .attr("src", e.thumb)
                                                .show(),
                                            n.append(o);
                                    }
                                });
                        },
                    }
                ),
            $(document).on(
                "click",
                ".btn-trigger-remove-product-image",
                function (e) {
                    e.preventDefault(),
                        $(e.currentTarget)
                            .closest(".product-image-item-handler")
                            .remove(),
                        0 ===
                            $(".list-gallery-media-images").find(
                                ".product-image-item-handler"
                            ).length &&
                            $(".default-placeholder-product-image").removeClass(
                                "hidden"
                            );
                }
            ),
            $(document).on(
                "click",
                ".list-search-data .selectable-item",
                function (e) {
                    e.preventDefault();
                    var t = $(e.currentTarget),
                        a = t.closest(".form-group").find("input[type=hidden]"),
                        r = a.val().split(",");
                    if (
                        ($.each(r, function (e, t) {
                            r[e] = parseInt(t);
                        }),
                        $.inArray(t.data("id"), r) < 0)
                    ) {
                        a.val()
                            ? a.val(a.val() + "," + t.data("id"))
                            : a.val(t.data("id"));
                        var n = $(document)
                            .find("#selected_product_list_template")
                            .html()
                            .replace(/__name__/gi, t.data("name"))
                            .replace(/__id__/gi, t.data("id"))
                            .replace(/__url__/gi, t.data("url"))
                            .replace(/__image__/gi, t.data("image"))
                            .replace(
                                /__attributes__/gi,
                                t.find("a span").text()
                            );
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
                }
            ),
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
            });
        var r = !1;
        $(document).on("keyup", ".textbox-advancesearch", function (t) {
            t.preventDefault();
            var a = $(t.currentTarget),
                n = a.closest(".box-search-advance").find(".panel");
            setTimeout(function () {
                Cmat.blockUI({ target: n, iconOnly: !0, overlayColor: "none" }),
                    r && e.abort(),
                    (r = !0),
                    (e = $.ajax({
                        url: a.data("target") + "&keyword=" + a.val(),
                        type: "GET",
                        success: function (e) {
                            e.error
                                ? Cmat.showError(e.message)
                                : (n.html(e.data), Cmat.unblockUI(n)),
                                (r = !1);
                        },
                        error: function (e) {
                            "abort" !== e.statusText &&
                                (Cmat.handleError(e), Cmat.unblockUI(n));
                        },
                    }));
            }, 500);
        }),
            $(document).on(
                "click",
                ".box-search-advance .page-link",
                function (e) {
                    e.preventDefault();
                    var t = $(e.currentTarget)
                        .closest(".box-search-advance")
                        .find(".textbox-advancesearch");
                    if (
                        !t.closest(".page-item").hasClass("disabled") &&
                        t.data("target")
                    ) {
                        var a = t.closest(".box-search-advance").find(".panel");
                        Cmat.blockUI({
                            target: a,
                            iconOnly: !0,
                            overlayColor: "none",
                        }),
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
                }
            ),
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
                    r > -1 && a.splice(r, 1),
                        t.val(a.join(",")),
                        $(e.currentTarget).closest("tbody").find("tr").length <
                            2 &&
                            $(e.currentTarget)
                                .closest(".list-selected-products")
                                .addClass("hidden"),
                        $(e.currentTarget).closest("tr").remove();
                }
            );
        $(document).ready(function () {
            var e;
            (e = $(".wrap-relation-product")).length &&
                (Cmat.blockUI({
                    target: e,
                    iconOnly: !0,
                    overlayColor: "none",
                }),
                $.ajax({
                    url: e.data("target"),
                    type: "GET",
                    success: function (t) {
                        t.error
                            ? Cmat.showError(t.message)
                            : (e.html(t.data), Cmat.unblockUI(e));
                    },
                    error: function (t) {
                        Cmat.handleError(t), Cmat.unblockUI(e);
                    },
                }));
        }),
            $(document).on("click", ".digital_attachments_btn", function (e) {
                e.preventDefault(),
                    $(e.currentTarget)
                        .closest(".product-type-digital-management")
                        .find("input[type=file]")
                        .last()
                        .trigger("click");
            }),
            $(document).on(
                "change",
                "input[name^=product_files_input]",
                function (e) {
                    var t = $(e.currentTarget),
                        a = t[0].files[0];
                    if (a) {
                        var r = t.closest(".product-type-digital-management"),
                            n = t.data("id"),
                            o = $("#digital_attachment_template").html();
                        o = o
                            .replace(/__id__/gi, n)
                            .replace(/__file_name__/gi, a.name)
                            .replace(
                                /__file_size__/gi,
                                (function (e) {
                                    var t =
                                            !(
                                                arguments.length > 1 &&
                                                void 0 !== arguments[1]
                                            ) || arguments[1],
                                        a =
                                            arguments.length > 2 &&
                                            void 0 !== arguments[2]
                                                ? arguments[2]
                                                : 2,
                                        r = t ? 1e3 : 1024;
                                    if (Math.abs(e) < r) return e + " B";
                                    var n = t
                                            ? [
                                                  "kB",
                                                  "MB",
                                                  "GB",
                                                  "TB",
                                                  "PB",
                                                  "EB",
                                                  "ZB",
                                                  "YB",
                                              ]
                                            : [
                                                  "KiB",
                                                  "MiB",
                                                  "GiB",
                                                  "TiB",
                                                  "PiB",
                                                  "EiB",
                                                  "ZiB",
                                                  "YiB",
                                              ],
                                        o = -1,
                                        i = Math.pow(10, a);
                                    do {
                                        (e /= r), ++o;
                                    } while (
                                        Math.round(Math.abs(e) * i) / i >= r &&
                                        o < n.length - 1
                                    );
                                    return (
                                        Cmat.numberFormat(e, a, ",", ".") +
                                        " " +
                                        n[o]
                                    );
                                })(a.size)
                            );
                        var i = Math.floor(26 * Math.random()) + Date.now();
                        r.find("table tbody").append(o),
                            r
                                .find(".digital_attachments_input")
                                .append(
                                    '<input type="file" name="product_files_input[]" data-id="'.concat(
                                        i,
                                        '">'
                                    )
                                );
                    }
                }
            ),
            $(document).on(
                "change",
                "input.digital-attachment-checkbox",
                function (e) {
                    var t = $(e.currentTarget),
                        a = t.closest("tr");
                    t.is(":checked")
                        ? a.removeClass("detach")
                        : a.addClass("detach");
                }
            ),
            $(document).on("click", ".remove-attachment-input", function (e) {
                var t = $(e.currentTarget).closest("tr"),
                    a = t.data("id");
                $("input[data-id=" + a + "]").remove(),
                    t.fadeOut(300, function () {
                        $(this).remove();
                    });
            });
    });
})();
