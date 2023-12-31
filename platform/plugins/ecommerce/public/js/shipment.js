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
    function e(e, n) {
        for (var o = 0; o < n.length; o++) {
            var r = n[o];
            (r.enumerable = r.enumerable || !1),
                (r.configurable = !0),
                "value" in r && (r.writable = !0),
                Object.defineProperty(
                    e,
                    ((a = r.key),
                    (i = void 0),
                    (i = (function (e, n) {
                        if ("object" !== t(e) || null === e) return e;
                        var o = e[Symbol.toPrimitive];
                        if (void 0 !== o) {
                            var r = o.call(e, n || "default");
                            if ("object" !== t(r)) return r;
                            throw new TypeError(
                                "@@toPrimitive must return a primitive value."
                            );
                        }
                        return ("string" === n ? String : Number)(e);
                    })(a, "string")),
                    "symbol" === t(i) ? i : String(i)),
                    r
                );
        }
        var a, i;
    }
    var n = (function () {
        function t() {
            !(function (t, e) {
                if (!(t instanceof e))
                    throw new TypeError("Cannot call a class as a function");
            })(this, t);
        }
        var n, o, r;
        return (
            (n = t),
            (o = [
                {
                    key: "init",
                    value: function () {
                        $(document).on(
                            "click",
                            ".shipment-actions .dropdown-menu a",
                            function (t) {
                                t.preventDefault();
                                var e = $(t.currentTarget);
                                $("#confirm-change-shipment-status-button")
                                    .data("target", e.data("target"))
                                    .data("status", e.data("value"));
                                var n = $("#confirm-change-status-modal");
                                n
                                    .find(".shipment-status-label")
                                    .text(e.text().toLowerCase()),
                                    n.modal("show");
                            }
                        ),
                            $(document).on(
                                "click",
                                "#confirm-change-shipment-status-button",
                                function (t) {
                                    t.preventDefault();
                                    var e = $(t.currentTarget);
                                    e.addClass("button-loading"),
                                        $.ajax({
                                            type: "POST",
                                            cache: !1,
                                            url: e.data("target"),
                                            data: { status: e.data("status") },
                                            success: function (t) {
                                                t.error
                                                    ? (Cmat.showError(
                                                          t.message
                                                      ),
                                                      e.removeClass(
                                                          "button-loading"
                                                      ))
                                                    : (Cmat.showSuccess(
                                                          t.message
                                                      ),
                                                      $(".max-width-1200").load(
                                                          window.location.href +
                                                              " .max-width-1200 > *",
                                                          function () {
                                                              $(
                                                                  "#confirm-change-status-modal"
                                                              ).modal("hide"),
                                                                  e.removeClass(
                                                                      "button-loading"
                                                                  );
                                                          }
                                                      ));
                                            },
                                            error: function (t) {
                                                Cmat.handleError(t),
                                                    e.removeClass(
                                                        "button-loading"
                                                    );
                                            },
                                        });
                                }
                            );
                    },
                },
            ]) && e(n.prototype, o),
            r && e(n, r),
            Object.defineProperty(n, "prototype", { writable: !1 }),
            t
        );
    })();
    $(document).ready(function () {
        new n().init();
    });
})();
