$(function () {
    var e = $(".alert.alert-warning");
    e.length > 0 &&
        _.map(e, function (e) {
            var t = localStorage.getItem("storage-alerts");
            if (((t = t ? JSON.parse(t) : {}), $(e).data("alert-id"))) {
                if (t[$(e).data("alert-id")]) return void $(e).alert("close");
                $(e).removeClass("hidden");
            }
        }),
        e.on("closed.bs.alert", function (e) {
            var t = $(e.target).data("alert-id");
            if (t) {
                var a = localStorage.getItem("storage-alerts");
                ((a = a ? JSON.parse(a) : {})[t] = !0),
                    localStorage.setItem("storage-alerts", JSON.stringify(a));
            }
        });
    var t = !1;
    $(document).on("click", ".download-template", function (e) {
        if ((e.preventDefault(), !t)) {
            var a = $(e.currentTarget),
                r = a.data("extension"),
                o = a.html();
            $.ajax({
                url: a.data("url"),
                method: "POST",
                data: { extension: r },
                xhrFields: { responseType: "blob" },
                beforeSend: function () {
                    a.html(a.data("downloading")),
                        a.addClass("text-secondary"),
                        (t = !0);
                },
                success: function (e) {
                    var t = document.createElement("a"),
                        r = window.URL.createObjectURL(e);
                    (t.href = r),
                        (t.download = a.data("filename")),
                        document.body.append(t),
                        t.click(),
                        t.remove(),
                        window.URL.revokeObjectURL(r);
                },
                error: function (e) {
                    Cmat.handleError(e);
                },
                complete: function () {
                    setTimeout(function () {
                        a.html(o), a.removeClass("text-secondary"), (t = !1);
                    }, 2e3);
                },
            });
        }
    }),
        $(document).on("submit", ".form-import-data", function (e) {
            e.preventDefault();
            var t = $(this),
                a = new FormData(t.get(0)),
                r = t.find("button[type=submit]");
            r.prop("disabled", !0).addClass("button-loading");
            var o = $("#imported-message"),
                n = $("#imported-listing"),
                s = $(".show-errors"),
                l = $("#failure-template").html();
            $.ajax({
                url: t.attr("action"),
                type: t.attr("method") || "POST",
                data: a,
                cache: !1,
                processData: !1,
                contentType: !1,
                dataType: "json",
                beforeSend: function () {
                    $(".main-form-message").addClass("hidden"),
                        o.html(""),
                        n.html("");
                },
                success: function (e) {
                    e.error
                        ? Cmat.showError(e.message)
                        : Cmat.showSuccess(e.message);
                    var t = "";
                    e.data.failures &&
                        _.map(e.data.failures, function (e) {
                            t += l
                                .replace("__row__", e.row)
                                .replace("__attribute__", e.attribute)
                                .replace("__errors__", e.errors.join(", "));
                        });
                    var a = "alert alert-success";
                    e.data.total_failed
                        ? ((a = e.data.total_success
                              ? "alert alert-warning"
                              : "alert alert-danger"),
                          s.removeClass("hidden"))
                        : s.addClass("hidden"),
                        o.removeClass().addClass(a).html(e.message),
                        t && n.removeClass("hidden").html(t),
                        (document.getElementById("input-group-file").value =
                            "");
                },
                error: function (e) {
                    Cmat.handleError(e);
                },
                complete: function () {
                    r.prop("disabled", !1),
                        r.removeClass("button-loading"),
                        $(".main-form-message").removeClass("hidden");
                },
            });
        });
});
