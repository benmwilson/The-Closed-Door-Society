window.onload = function () {
    var userInfo = $("#userinfo");
    var registrationForm = $("#registration-form");

    $(userInfo).submit(function (e) {
        var usernameField = $("#userinfo #username");
        var passwordField = $("#userinfo #password");

        //Check if username field is empty
        if (usernameField.val() == "") {
            e.preventDefault();
            $(usernameField).css("border-color", "#d00");
        } else {
            $(usernameField).css("border-color", "#444444");
        }
        //Check if password field is empty
        if (passwordField.val() == "") {
            e.preventDefault();
            $(passwordField).css("border-color", "#d00");
        } else {
            $(passwordField).css("border-color", "#444444");
        }
    })

    console.log(registrationForm);

    $(registrationForm).submit(function (e) {
        var usernameField = $("#registration-form #username");
        var emailField = $("#registration-form #email");
        var passwordField = $("#registration-form #password");
        var imageField = $("#registration-form #userimg");

        //Check if username field is empty
        if (usernameField.val() == "") {
            e.preventDefault();
            $(usernameField).css("border-color", "#d00");
        } else {
            $(usernameField).css("border-color", "#444444");
        }
        //Check if email field is empty
        if (emailField.val() == "") {
            e.preventDefault();
            $(emailField).css("border-color", "#d00");
        } else {
            $(emailField).css("border-color", "#444444");
        }
        //Check if password field is empty
        if (passwordField.val() == "") {
            e.preventDefault();
            $(passwordField).css("border-color", "#d00");
        } else {
            $(passwordField).css("border-color", "#444444");
        }
        //Check if image field is empty
        if (imageField.val() == "") {
            e.preventDefault();
            $(imageField).css("border-color", "#d00");
        } else {
            $(imageField).css("border-color", "#444444");
        }
    })
}