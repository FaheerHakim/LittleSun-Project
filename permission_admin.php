<?php
if ($_SESSION['user']['type_user'] !== 'admin') {
    // Redirect or display an error message
    echo "You do not have permission.";
    exit;
}
?>