(() => {
    function o(t) {
        return (
            (o =
                "function" == typeof Symbol &&
                "symbol" == typeof Symbol.iterator
                    ? function (o) {
                          return typeof o;
                      }
                    : function (o) {
                          return o &&
                              "function" == typeof Symbol &&
                              o.constructor === Symbol &&
                              o !== Symbol.prototype
                              ? "symbol"
                              : typeof o;
                      }),
            o(t)
        );
    }
    function t(t, e) {
        for (var r = 0; r < e.length; r++) {
            var a = e[r];
            (a.enumerable = a.enumerable || !1),
                (a.configurable = !0),
                "value" in a && (a.writable = !0),
                Object.defineProperty(
                    t,
                    ((n = a.key),
                    (l = void 0),
                    (l = (function (t, e) {
                        if ("object" !== o(t) || null === t) return t;
                        var r = t[Symbol.toPrimitive];
                        if (void 0 !== r) {
                            var a = r.call(t, e || "default");
                            if ("object" !== o(a)) return a;
                            throw new TypeError(
                                "@@toPrimitive must return a primitive value."
                            );
                        }
                        return ("string" === e ? String : Number)(t);
                    })(n, "string")),
                    "symbol" === o(l) ? l : String(l)),
                    a
                );
        }
        var n, l;
    }
    var e = (function () {
        function o() {
            !(function (o, t) {
                if (!(o instanceof t))
                    throw new TypeError("Cannot call a class as a function");
            })(this, o);
        }
        var e, r, a;
        return (
            (e = o),
            (r = [
                {
                    key: "init",
                    value: function () {
                        $(document).on(
                            "click",
                            ".btn-trigger-show-store-locator",
                            function (o) {
                                o.preventDefault();
                                var t,
                                    e = $(o.currentTarget);
                                (t =
                                    "update" === e.data("type")
                                        ? $(
                                              "#update-store-locator-modal .modal-body"
                                          )
                                        : $(
                                              "#add-store-locator-modal .modal-body"
                                          )),
                                    $.ajax({
                                        url: e.data("load-form"),
                                        type: "GET",
                                        beforeSend: function () {
                                            e.addClass("button-loading"),
                                                t.html("");
                                        },
                                        success: function (o) {
                                            o.error
                                                ? Cmat.showError(o.message)
                                                : (t.html(o.data),
                                                  Cmat.initResources(),
                                                  t
                                                      .closest(".modal.fade")
                                                      .modal("show")),
                                                e.removeClass("button-loading");
                                        },
                                        complete: function () {
                                            e.removeClass("button-loading");
                                        },
                                        error: function (o) {
                                            e.removeClass("button-loading"),
                                                Cmat.handleError(o);
                                        },
                                    });
                            }
                        );
                        var o = function (o) {
                            o.addClass("button-loading"),
                                $.ajax({
                                    type: "POST",
                                    cache: !1,
                                    url: o
                                        .closest(".modal-content")
                                        .find("form")
                                        .prop("action"),
                                    data: o
                                        .closest(".modal-content")
                                        .find("form")
                                        .serialize(),
                                    success: function (t) {
                                        t.error
                                            ? (Cmat.showError(t.message),
                                              o.removeClass("button-loading"))
                                            : (Cmat.showSuccess(t.message),
                                              $(".store-locator-wrap").load(
                                                  window.location.href +
                                                      " .store-locator-wrap > *"
                                              ),
                                              o.removeClass("button-loading"),
                                              o
                                                  .closest(".modal.fade")
                                                  .modal("hide"));
                                    },
                                    error: function (t) {
                                        Cmat.handleError(t),
                                            o.removeClass("button-loading");
                                    },
                                });
                        };
                        $(document).on(
                            "click",
                            "#add-store-locator-button",
                            function (t) {
                                t.preventDefault(), o($(t.currentTarget));
                            }
                        ),
                            $(document).on(
                                "click",
                                "#update-store-locator-button",
                                function (t) {
                                    t.preventDefault(), o($(t.currentTarget));
                                }
                            ),
                            $(document).on(
                                "click",
                                ".btn-trigger-delete-store-locator",
                                function (o) {
                                    o.preventDefault(),
                                        $("#delete-store-locator-button").data(
                                            "target",
                                            $(o.currentTarget).data("target")
                                        ),
                                        $("#delete-store-locator-modal").modal(
                                            "show"
                                        );
                                }
                            ),
                            $(document).on(
                                "click",
                                "#delete-store-locator-button",
                                function (o) {
                                    o.preventDefault();
                                    var t = $(o.currentTarget);
                                    t.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: t.data("target"),
                                            success: function (o) {
                                                o.error
                                                    ? (Cmat.showError(
                                                          o.message
                                                      ),
                                                      t.removeClass(
                                                          "button-loading"
                                                      ))
                                                    : (Cmat.showSuccess(
                                                          o.message
                                                      ),
                                                      $(
                                                          ".store-locator-wrap"
                                                      ).load(
                                                          window.location.href +
                                                              " .store-locator-wrap > *"
                                                      ),
                                                      t.removeClass(
                                                          "button-loading"
                                                      ),
                                                      t
                                                          .closest(
                                                              ".modal.fade"
                                                          )
                                                          .modal("hide"));
                                            },
                                            error: function (o) {
                                                Cmat.handleError(o),
                                                    t.removeClass(
                                                        "button-loading"
                                                    );
                                            },
                                        });
                                }
                            ),
                            $(document).on(
                                "click",
                                "#change-primary-store-locator-button",
                                function (o) {
                                    o.preventDefault();
                                    var t = $(o.currentTarget);
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
                                            success: function (o) {
                                                o.error
                                                    ? (Cmat.showError(
                                                          o.message
                                                      ),
                                                      t.removeClass(
                                                          "button-loading"
                                                      ))
                                                    : (Cmat.showSuccess(
                                                          o.message
                                                      ),
                                                      $(
                                                          ".store-locator-wrap"
                                                      ).load(
                                                          window.location.href +
                                                              " .store-locator-wrap > *"
                                                      ),
                                                      t.removeClass(
                                                          "button-loading"
                                                      ),
                                                      t
                                                          .closest(
                                                              ".modal.fade"
                                                          )
                                                          .modal("hide"));
                                            },
                                            error: function (o) {
                                                Cmat.handleError(o),
                                                    t.removeClass(
                                                        "button-loading"
                                                    );
                                            },
                                        });
                                }
                            );
                    },
                },
            ]) && t(e.prototype, r),
            a && t(e, a),
            Object.defineProperty(e, "prototype", { writable: !1 }),
            o
        );
    })();
    $(document).ready(function () {
        new e().init();
    });
})();
