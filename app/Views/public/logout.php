<?php
session_start();
session_destroy();
header('Location: /../Views/auth/login');
exit();
?>
