<<<<<<< HEAD
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: pages/dashboard.php');
} else {
    header('Location: pages/login.php');
}
exit();
?>
=======
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: pages/dashboard.php');
} else {
    header('Location: pages/login.php');
}
exit();
?>
>>>>>>> ccc14c2 (Initial commit)
