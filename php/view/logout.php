<?php
session_start();
session_destroy();

// Elimina cookies de sesión
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Evitar caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirige y limpia caché en el navegador
echo "<script>
    localStorage.clear();
    sessionStorage.clear();
    window.location.href = 'login.php?nocache=' + new Date().getTime();
</script>";
exit();
?>
