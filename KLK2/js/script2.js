$(document).ready(function () {
    $("#registerForm").on("submit", function (e) {
        e.preventDefault();

        const firstName = $("#first_name").val();
        const lastName = $("#last_name").val();
        const email = $("#email").val();
        const password = $("#password").val();

        registerUser(firstName, lastName, email, password);
    });
});

function registerUser(firstName, lastName, email, password) {
    $.ajax({
        url: "./backend/register.php",
        method: "POST",
        data: {
            first_name: firstName,
            last_name: lastName,
            email: email,
            password: password
        },
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                $("#registerSuccess").text(response.message);
                $("#registerError").text('');
                setTimeout(() => {
                    window.location.href = './index.html';
                }, 2000);
            } else {
                $("#registerError").text(response.message);
                $("#registerSuccess").text('');
            }
        },
        error: function () {
            $("#registerError").html("No se pudo conectar con el servidor.");
        }
    });
}
