$(function () {
    $(document).on("click", ".btn-trigger-add-address", function (e) {
        e.preventDefault();
        $("#add-address-modal").modal("show");
    });

    $(document).on("click", "#confirm-add-address-button", (event) => {
        event.preventDefault();
        let _self = $(event.currentTarget);
        _self.addClass("button-loading");

        $.ajax({
            type: "POST",
            cache: false,
            url: _self.closest(".modal-content").find("form").prop("action"),
            data: _self.closest(".modal-content").find("form").serialize(),
            success: (res) => {
                if (!res.error) {
                    Cmat.showNotice("success", res.message);
                    $("#add-address-modal").modal("hide");
                    _self.closest(".modal-content").find("form").get(0).reset();
                    $("#address-histories").load(
                        $(".page-content form").prop("action") +
                            " #address-histories > *"
                    );
                } else {
                    Cmat.showNotice("error", res.message);
                }
                _self.removeClass("button-loading");
            },
            error: (res) => {
                Cmat.handleError(res);
                _self.removeClass("button-loading");
            },
        });
    });

    $(document).on("click", ".btn-trigger-edit-address", (event) => {
        event.preventDefault();
        let _self = $(event.currentTarget);

        $.ajax({
            type: "GET",
            cache: false,
            url: _self.data("section"),
            success: (res) => {
                if (!res.error) {
                    $("#edit-address-modal .modal-body").html("");
                    $("#edit-address-modal .modal-body").append(res);
                    $("#edit-address-modal").modal("show");
                } else {
                    Cmat.showNotice("error", res.message);
                }
                _self.removeClass("button-loading");
            },
            error: (res) => {
                Cmat.handleError(res);
                _self.removeClass("button-loading");
            },
        });
    });

    $(document).on("click", "#confirm-edit-address-button", (event) => {
        event.preventDefault();
        let _self = $(event.currentTarget);
        _self.addClass("button-loading");

        $.ajax({
            type: "POST",
            cache: false,
            url: _self.closest(".modal-content").find("form").prop("action"),
            data: _self.closest(".modal-content").find("form").serialize(),
            success: (res) => {
                if (!res.error) {
                    Cmat.showNotice("success", res.message);
                    $("#edit-address-modal").modal("hide");
                    _self.closest(".modal-content").find("form").get(0).reset();
                    $("#address-histories").load(
                        $(".page-content form").prop("action") +
                            " #address-histories > *"
                    );
                } else {
                    Cmat.showNotice("error", res.message);
                }
                _self.removeClass("button-loading");
            },
            error: (res) => {
                Cmat.handleError(res);
                _self.removeClass("button-loading");
            },
        });
    });

    $(document).on("click", ".deleteDialog", function (event) {
        event.preventDefault();
        var _self = $(event.currentTarget);
        $(".delete-crud-entry").data("section", _self.data("section"));
        $(".modal-confirm-delete").modal("show");
    });

    $(".delete-crud-entry").on("click", function (event) {
        event.preventDefault();
        const _self = $(event.currentTarget);
        _self.addClass("button-loading");
        const deleteURL = _self.data("section");
        $.ajax({
            url: deleteURL,
            type: "POST",
            data: {
                _method: "DELETE",
            },
            success: function (data) {
                if (data.error) {
                    Cmat.showError(data.message);
                } else {
                    Cmat.showSuccess(data.message);
                    const formAction = $(".page-content form").prop("action");
                    $("#address-histories").load(
                        formAction + " #address-histories > *"
                    );
                }
                _self.closest(".modal").modal("hide");
                _self.removeClass("button-loading");
            },
            error: function error(data) {
                Cmat.handleError(data);
                _self.removeClass("button-loading");
            },
        });
    });
});
