<?php
session_start();
session_destroy();
header('Location: index.html'); // Redirect to your home page
exit();
?>