console.log("JS cargado correctamente");

const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

registerBtn.addEventListener('click', () =>{
    container.classList.add('active');
});

loginBtn.addEventListener('click', () =>{
    container.classList.remove('active');
}); 

$(document).ready(function () {
    // LOGUEAR
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

/* registro de usuarios */
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


