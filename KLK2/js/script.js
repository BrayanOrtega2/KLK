$(document).ready(function () {
    // LOGIN
    $("#loginForm").submit(function (event) {
        event.preventDefault();

        var username = $.trim($('input[name="email"]').val());
        var password = $.trim($('input[name="password"]').val());

        password = encodeURIComponent(password);

        loginUser(username, password);
    });
});

// FUNCION LOGIN SEPARADA
function loginUser(username, password) {
    $.ajax({
        url: "./backend/login.php",
        method: "POST",
        data: { username: username, password: password },
        dataType: "json",
        success: function (response) {
            console.log("Respuesta del login:");
            console.log(response);

            $("#loginError").html("");

            if (response.status === "success") {
                window.location.href = "./views/home.php";
            } else if (response.status === "error") {
                $("#loginError").html(response.message);
                $('input[name="email"]').val("");
                $('input[name="password"]').val("");
            } else {
                $("#loginError").html("Error desconocido al iniciar sesión");
            }
        },
        error: function () {
            $("#loginError").html("No se pudo conectar con el servidor.");
        }
    });
}


