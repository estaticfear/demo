$(function () {
    var e = !1;
    $(document).on("click", ".btn-export-data", function (t) {
        if ((t.preventDefault(), !e)) {
            var n = $(t.currentTarget),
                o = n.html();
            $.ajax({
                url: n.attr("href"),
                method: "POST",
                xhrFields: { responseType: "blob" },
                beforeSend: function () {
                    n.html(n.data("loading-text")),
                        n.attr("disabled", "true"),
                        (e = !0);
                },
                success: function (e) {
                    var t = document.createElement("a"),
                        o = window.URL.createObjectURL(e);
                    (t.href = o),
                        (t.download = n.data("filename")),
                        document.body.append(t),
                        t.click(),
                        t.remove(),
                        window.URL.revokeObjectURL(o);
                },
                error: function (e) {
                    Cmat.handleError(e);
                },
                complete: function () {
                    setTimeout(function () {
                        n.html(o), n.removeAttr("disabled"), (e = !1);
                    }, 2e3);
                },
            });
        }
    });
});
