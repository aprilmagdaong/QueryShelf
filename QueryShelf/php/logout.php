<?php
session_start();
session_destroy();
header("Location: /QueryShelf/index.php");
exit();
?>