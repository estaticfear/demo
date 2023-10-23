/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Cmat from "./utils";
import ActivityLogComponent from "./components/dashboard/ActivityLogComponent.vue";

require("./bootstrap");

/**
 * Next, we will create a fresh Vue application instance and attach it to O
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

if (typeof vueApp !== "undefined") {
    vueApp.booting((vue) => {
        vue.component("activity-log-component", ActivityLogComponent);
    });
}

require("./form");
require("./avatar");

$(document).ready(() => {
    if (window.noticeMessages) {
        window.noticeMessages.forEach((message) => {
            Cmat.showNotice(
                message.type,
                message.message,
                message.type === "error"
                    ? _.get(window.trans, "notices.error")
                    : _.get(window.trans, "notices.success")
            );
        });
    }
});

$(document).ready(function () {
    $("#show-and-hide-pass").on("click", function (event) {
        event.preventDefault();
        if ($("#show_hide_password input").attr("type") == "text") {
            $("#show_hide_password input").attr("type", "password");
            $("#show_hide_password i").addClass("fa-eye-slash");
            $("#show_hide_password i").removeClass("fa-eye");
        } else if ($("#show_hide_password input").attr("type") == "password") {
            $("#show_hide_password input").attr("type", "text");
            $("#show_hide_password i").removeClass("fa-eye-slash");
            $("#show_hide_password i").addClass("fa-eye");
        }
    });
});
