<?php
session_start();
// Destruir sesión
session_unset();
session_destroy();
header("Location: ../views/out.php");
exit();
?>