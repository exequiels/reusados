$(document).ready(function() {
    $('#registro').on('submit', function(event) {
    
        var username = $('#username').val();
        var fullname = $('#fullname').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        var captcha = parseInt($('#captcha').val());
        var isValid = true;

        $('.validacion').hide();

        if (username.length < 3 || username.length > 30) {
            $('#username').siblings('.validacion').show();
            isValid = false;
        }

        if (fullname.length < 3 || fullname.length > 30) {
            $('#fullname').siblings('.validacion').show();
            isValid = false;
        }

        if (!validateEmail(email) || email.length > 50) {
            $('#email').siblings('.validacion').show();
            isValid = false;
        }

        if (!validatePassword(password) || password.length > 60) {
            $('#password').siblings('.validacion').show();
            isValid = false;
        }

        if (password !== confirmPassword) {
            $('#confirm_password').siblings('.validacion').show();
            isValid = false;
        }

        if (captcha !== captchaAnswer) {
            $('#captcha').siblings('.validacion').show();
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    function validateEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    function validatePassword(password) {
        var re = /^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;
        return re.test(password);
    }
});