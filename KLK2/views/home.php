<?php
session_start();

if (isset($_SESSION['logged_in']) &&  $_SESSION['logged_in'] === true) {
    // El usuario está autenticado
    $id = $_SESSION['id'];
    $roleId = $_SESSION['role_id'];
    $email = $_SESSION['email'];
    $full_name = $_SESSION['fname'];
    $last_name = $_SESSION['lastname'];
} else {
    header("Location: ../index.html");
    exit();
}


?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style5.css">

    <title>KLK-<?php echo $full_name; ?></title>
</head>

<body>
    <div class="stay" id="parent4" style="background-color: #5a5e9a; z-index:3;">
        <nav class="navbar navbar-expand-lg sticky-top" style=" background-color: #5a5e9a; ">
            <div class="container-fluid">
                <div class="logoh">
                    <img src="../assets/klk-banner.png" alt="KLK Logo">
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled=true><?php echo $full_name; ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true"><?php echo $last_name; ?></a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Options
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">add</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../backend/logout.php">log out</a></li>

                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">mensagges</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="butt btn-xs" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
    <!-- contenedor principal -->

    <!-- fila 2 -->
    <div id="leftbar">
        <section class="hero">
            <div class="hero-content">
                <h6>Comparte lo que quieras</h6>
                <p>con KLK</p>
                <div class="text-center my-4">
                    <button id="showForm" class="btn butt">Subir Foto</button>
                </div>

                <!-- Formulario oculto -->
                <div id="formsPhotos" class="container" style="display: none;">
                    <form id="formPhoto" enctype="multipart/form-data" class="p-3 border rounded shadow-sm bg-light">
                        <div class="mb-3">
                            <label for="photo" class="form-label" style="color: #5a5e9a;">Selecciona una foto:</label>
                            <input type="file" name="photo" id="photo" class="form-control form-control-sm" style="width: 150px;">

                            
                        </div>
                        <button type="submit" class="btn butt">Subir</button>
                    </form>
                    <div id="answer" class="mt-3"></div>
                </div>
            </div>
        </section>
    </div>

    <!-- Galería a la derecha -->
    <div id="content">
        <div class="container">
            <div id="gallery" class="row g-6"></div>
            <div id="comments-${photo.id}" class="mt-2 text-start" style="display:none; color: #ffffff;"></div>
        </div>
    </div>


    <!-- FIN columna 1 fila 2 -->

    <!-- columna 2 fila 2 -->

    <div class=" col-md-3 col-lg-3 justify-content-center text-center sidebar" style="background: linear-gradient(135deg, #272829, #272829);">
        <div class="row">

            <div class="col-md-3 col-lg-3 sidebar">
                <div class="features">
                    <div class="feature">
                        <h3>📸 Comparte Fotos</h3>
                        <p>Sube tus mejores momentos y comparte con tus amigos.</p>
                    </div>
                    <div class="feature">
                        <h3>💬 Chatea en Vivo</h3>
                        <p>Envía mensajes y mantente conectado con quien quieras.</p>
                    </div>
                    <div class="feature">
                        <h3>🌎 Explora</h3>
                        <p>Descubre contenido y conoce nuevas personas alrededor del mundo.</p>
                    </div>
                </div>
            </div>


        </div>
        <!-- FIN columna 2 fila 2 -->

    </div>
    <!-- FIN fila 2 -->






    <!-- FIN fila 3 -->

    </div>
    <!--FIN  contenedor principal -->

    <!------------------------------- -->

    <!-- Bootstrap Bundle with Popper -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




</body>

</html>