<?php
session_start();
include '../backend/config.php';

$db = new DbConfig();
$conn = $db->getConnection();

// Verificación de sesión iniciada
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $id = $_SESSION['id'];
    $roleId = $_SESSION['role_id'];
    $email = $_SESSION['email'];
    $full_name = $_SESSION['fname'];
    $last_name = $_SESSION['lastname'];

    // Consulta para obtener los usuarios, excluyendo al actual
    $stmt = $conn->prepare("SELECT id, full_name, last_name FROM users WHERE id != :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Redirección si no hay sesión iniciada
    header("Location: ../index.html");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Metadatos necesarios -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="..." crossorigin="anonymous">

    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../css/style5.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/favicon_io/favicon-32x32.png" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon_io/site.webmanifest">

    <!-- Título de la página personalizado con el nombre del usuario -->
    <title>KLK-<?php echo $full_name; ?></title>
</head>

<body data-user-id="<?php echo htmlspecialchars($id); ?>">

    <!-- Contenedor del encabezado con barra de navegación -->
    <div class="parent4" id="parent4">
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <!-- Logo -->
                <div class="logoh">
                    <img src="../assets/klk-banner.jpg" alt="KLK Logo">
                </div>
                <!-- Botón colapsable para móviles -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Elementos de navegación -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- Espacio o separador -->
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled=true></a>
                        </li>
                        <!-- Mostrar apellido del usuario -->
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true"><?php echo $full_name, " ", $last_name; ?></a>
                        </li>

                        <!-- Menú desplegable de opciones -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Options
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">profile</a></li>
                                <li><a class="dropdown-item" href="#" >support</a></li>
                                
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../backend/logout.php">log out</a></li>
                            </ul>
                        </li>

                        <!-- Enlace a mensajes -->
                        <li class="nav-item">
                            <a class="nav-link" href="./contacts.php">Messages</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#" id="trigger-upload">Upload images</a>
                            <input type="file" id="photoInput" name="photo" accept="image/*" style="display: none;">
                        </li>
                    </ul>


                </div>
            </div>
        </nav>
    </div>
    <!-- Fin del contenedor del encabezado -->

    <!-- Sidebar lateral izquierdo -->
    <div class="sidebar-fixed text-dark">
        <!-- Enlace con logo y texto -->
        <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">

            <span class="fs-4">KLKツ BETA 1.0.2.1</span>
        </a>
        <hr>
        <!-- Menú lateral de navegación -->
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link active text-dark" aria-current="page">
                    <i class="bi bi-house-door me-2"></i> Home
                </a>
            </li>
            <li><a href="./contacts.php" class="nav-link text-dark"><i class="bi bi-chat-dots"></i> Messages</a></li>
            <li><a href="#" class="nav-link text-dark"><i class="bi bi-gear-fill"></i> Settings</a></li>
            <li><a href="#" class="nav-link text-dark"><i class="bi bi-people me-2"></i> Users</a></li>
            <li><a href="#" class="nav-link text-dark"><i class="bi bi-info-circle"></i> Support</a></li>

        </ul>

        <!-- Sección inferior con usuario -->
        <div class="mt-auto">
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://t3.ftcdn.net/jpg/05/53/79/60/360_F_553796090_XHrE6R9jwmBJUMo9HKl41hyHJ5gqt9oz.jpg" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong><?php echo $full_name;
                            echo " ";
                            echo $last_name;
                            echo " "; ?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">

                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="../backend/logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Fin del sidebar -->

    <!-- Contenido principal (galería de imágenes) -->
    <div id="content">
        <div class="container">
            <div id="gallery" class="row g-6"></div>
            <div id="comments-${photo.id}" class="mt-2 text-start"></div>
        </div>
    </div>

    <!-- Columna vacía para insertar lista de usuarios en el futuro -->
    <div class="klk-card-wrapper position-fixed top-0 end-0 pt-5 pe-3 d-none d-md-block">
  <div class="d-flex flex-column align-items-end cards-klk">

    <div class="card klk-card mb-3 card-welcome text-center">
      <div class="card-header klk-header klk-header-welcome">Welcome to KLK!</div>
      <div class="card-body klk-body klk-body-welcome">
        <h5 class="card-title">What you can do</h5>
        <p class="card-text">Upload and share your best pictures.</p>
      </div>
    </div>

    <div class="card klk-card mb-3 card-tips text-center">
      <div class="card-header klk-header klk-header-tips">Tips</div>
      <div class="card-body klk-body klk-body-tips">
        <h5 class="card-title">Connect with others</h5>
        <p class="card-text">Send messages to users and build your network.</p>
      </div>
    </div>

    <div class="card klk-card mb-3 card-try text-center">
      <div class="card-header klk-header klk-header-try">Try this</div>
      <div class="card-body klk-body klk-body-try">
        <h5 class="card-title">Profile Customization</h5>
        <p class="card-text">Update your profile and make it stand out.</p>
      </div>
    </div>

    <div class="card klk-card mb-3 card-pro text-center">
      <div class="card-header klk-header klk-header-pro">Pro Tip</div>
      <div class="card-body klk-body klk-body-pro">
        <h5 class="card-title">Use the Upload Tool</h5>
        <p class="card-text">Easily upload multiple images in seconds.</p>
      </div>
    </div>

  </div>



    <!-- Scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>