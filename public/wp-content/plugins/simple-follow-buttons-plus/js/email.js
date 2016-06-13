jQuery(document).ready(function($) {

    $("body").on("click", ".sfbp-email", function () {
        $("#sfbp-email-div").fadeIn(500);
    });
    $("body").on("click", ".sfbp-close-email-div", function () {
        $("#sfbp-email-div").fadeOut(500);
    });

    function sfbp_email_alert(alert_message, alert_class) {
        // show alert
        $("#sfbp-email-alert").html(alert_message).addClass(alert_class).fadeIn();
    }

    function sfbp_email_hide_form() {
        // hide send button and form
        $("#js-sfbp-email-form").fadeOut();
    }

    $("#js-sfbp-email-form").on("submit", function (e) {

        // dont submit the form
        e.preventDefault();

        // disable all inputs
        $(":input").prop("disabled", true);

        // show spinner to show progress
        $("#sfbp-email-send").html("<span class=\'sfbp-spinner\'></span>");

        // prepare data
        var data = {
            action: "sfbp_email_send",
            security: sfbpEmail.security,
            fill_me: $("#js-sfbp-email-form #fill_me").val(),
            email: $("#js-sfbp-email-form #email").val(),
            message: $("#js-sfbp-email-form #message").val(),
            url: $("#js-sfbp-email-form #url").val()
        };

        // post
        $.post(sfbpEmail.ajax_url, data, function (response) {
            // honeypot?
            if (response == "bot") {
                // remove modal
                $("#sfbp-email-div").hide().html("");
            } else if (response == "check") {
                // set alert vars
                var alert_message = $("#js-sfbp-email-form").attr('data-warning-alert-text');
                var alert_class = "sfbp-alert-warning";

                // show alert
                sfbp_email_alert(alert_message, alert_class);

                // re-enable fields
                $(":input").prop("disabled", false);

                // reset button content
                $("#sfbp-email-send").html("Send");
            }

            if (response == "brute") {
                // set alert vars
                var alert_message = $("#js-sfbp-email-form").attr('data-brute-alert-text');
                var alert_class = "sfbp-alert-warning";

                // show alert
                sfbp_email_alert(alert_message, alert_class);

                // hide the form
                sfbp_email_hide_form();
            } else if (response == "success") {
                // set alert vars
                var alert_message = $("#js-sfbp-email-form").attr('data-success-alert-text');
                var alert_class = "sfbp-alert-success";

                // show success message
                sfbp_email_alert(alert_message, alert_class);

                // hide the form
                sfbp_email_hide_form();
            }
        }); // end post
    }); // end form submit
});
