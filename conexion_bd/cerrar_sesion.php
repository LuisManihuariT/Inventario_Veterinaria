<?php
session_start();
session_unset();
session_destroy();
header('Location: ../index.html'); // Redirigir al inicio de sesión
exit();
?>
