<?php
// Redirect to public/index.php
header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/public/index.php');
exit;
?>
